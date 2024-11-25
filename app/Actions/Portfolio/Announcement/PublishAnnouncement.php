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

        $updateData = [
            'live_snapshot_id'        => $snapshot->id,
            'fields'                  => Arr::get($snapshot->layout, 'fields'),
            'container_properties'    => Arr::get($snapshot->layout, 'container_properties'),
            'published_checksum'      => md5(json_encode($snapshot->layout)),
            'state'                   => AnnouncementStateEnum::READY,
            'settings'                => Arr::get($snapshot->layout, 'settings'),
            'is_dirty'                 => false
        ];

        if ($announcement->state == AnnouncementStateEnum::IN_PROCESS or $announcement->state == AnnouncementStateEnum::READY) {
            $updateData['ready_at'] = now();
            $updateData['live_at']  = now();
        }

        if ($scheduleAt = Arr::get($modelData, 'schedule_at')) {
            $updateData['schedule_at'] = $scheduleAt;
            $updateData['live_at']     = $scheduleAt;
        }

        if ($scheduleFinishAt = Arr::get($modelData, 'schedule_finish_at')) {
            $updateData['schedule_finish_at'] = $scheduleFinishAt;
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
            'fields'               => ['sometimes', 'array'],
            'container_properties' => ['sometimes', 'array']
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
