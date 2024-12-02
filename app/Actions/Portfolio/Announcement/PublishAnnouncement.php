<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement;

use App\Actions\Helpers\Deployment\StoreDeployment;
use App\Actions\Helpers\Snapshot\StoreAnnouncementSnapshot;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Helpers\Snapshot\SnapshotStateEnum;
use App\Enums\Portfolio\Announcement\AnnouncementStateEnum;
use App\Models\Announcement;
use App\Models\CRM\Customer;
use App\Models\Helpers\Snapshot;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class PublishAnnouncement
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    private Customer|PortfolioWebsite $parent;
    private string $scope;
    private Customer $customer;

    public function handle(Announcement $announcement, array $modelData): void
    {
        $firstCommit = false;
        if ($announcement->state == AnnouncementStateEnum::IN_PROCESS or $announcement->state == AnnouncementStateEnum::READY) {
            $firstCommit = true;
        }

        $layout = $announcement->unpublishedSnapshot->layout;

        /** @var Snapshot $snapshot */
        $snapshot = StoreAnnouncementSnapshot::run(
            $announcement,
            [
                'state'          => SnapshotStateEnum::LIVE,
                'published_at'   => now(),
                'layout'         => $layout,
                'first_commit'   => $firstCommit,
                'comment'        => Arr::get($modelData, 'comment'),
                'publisher_id'   => Arr::get($modelData, 'publisher_id'),
                'publisher_type' => Arr::get($modelData, 'publisher_type'),
            ]
        );

        StoreDeployment::run(
            $announcement,
            [
                'snapshot_id'    => $snapshot->id,
                'publisher_id'   => Arr::get($modelData, 'publisher_id'),
                'publisher_type' => Arr::get($modelData, 'publisher_type'),
            ]
        );

        $compiled_layout = [];
        if (Arr::exists($modelData, 'compiled_layout')) {
            $compiled_layout = [
                'compiled_layout'          => Arr::get($modelData, 'compiled_layout'),
            ];
        }

        $updateData = [
            'live_snapshot_id'         => $snapshot->id,
            'fields'                   => Arr::get($snapshot->layout, 'fields'),
            'published_message'        => Arr::get($modelData, 'published_message'),
            'container_properties'     => Arr::get($modelData, 'container_properties'),
            'text'                     => Arr::get($snapshot->layout, 'text'),
            'published_checksum'       => md5(json_encode($snapshot->layout)),
            'state'                    => AnnouncementStateEnum::READY,
            'settings'                 => Arr::get($snapshot->layout, 'settings'),
            'is_dirty'                 => false,
            ...$compiled_layout
        ];

        if ($announcement->state == AnnouncementStateEnum::IN_PROCESS or $announcement->state == AnnouncementStateEnum::READY) {
            $updateData['ready_at'] = now();
            $updateData['live_at']  = now();
        }

        $scheduleAt                = Arr::get($modelData, 'schedule_at');
        $updateData['schedule_at'] = $scheduleAt;

        if ($scheduleAt) {
            $updateData['live_at'] = $scheduleAt;
        }

        $scheduleFinishAt                 = Arr::get($modelData, 'schedule_finish_at');
        $updateData['schedule_finish_at'] = $scheduleFinishAt;

        if ($scheduleFinishAt) {
            $updateData['closed_at']          = $scheduleFinishAt;
        }

        ActivateAnnouncement::dispatch($announcement)->delay($updateData['live_at']);

        $this->update($announcement, $updateData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function rules(): array
    {
        return [
            'code'                 => ['sometimes', 'string'],
            'schedule_at'          => ['sometimes', 'string', 'nullable'],
            'schedule_finish_at'   => ['sometimes', 'string', 'nullable'],
            'published_message'    => ['sometimes', 'string'],
            'fields'               => ['sometimes', 'array'],
            'container_properties' => ['sometimes', 'array'],
            'compiled_layout'      => ['sometimes', 'string'],
            'text'                 => ['sometimes', 'string']
        ];
    }

    public function asController(PortfolioWebsite $portfolioWebsite, Announcement $announcement, ActionRequest $request): void
    {
        $this->scope    = 'portfolio-website';
        $this->parent   = $portfolioWebsite;
        $request->validate();

        $this->handle($announcement, $request->validated());
    }
}
