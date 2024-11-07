<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 10:05:33 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Helpers\Deployment\StoreDeployment;
use App\Actions\Helpers\Snapshot\StoreWebsiteSnapshot;
use App\Actions\Helpers\Snapshot\UpdateSnapshot;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Helpers\Snapshot\SnapshotStateEnum;
use App\Events\BroadcastPreviewHeaderFooter;
use App\Models\Helpers\Snapshot;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class PublishPortfolioWebsiteMarginal
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(PortfolioWebsite $portfolioWebsite, string $marginal, array $modelData): void
    {
        $layout      = Arr::get($modelData, 'layout');
        $firstCommit = true;

        foreach ($portfolioWebsite->snapshots()->where('scope', $marginal)->where('state', SnapshotStateEnum::LIVE)->get() as $liveSnapshot) {
            $firstCommit = false;
            UpdateSnapshot::run($liveSnapshot, [
                'state'           => SnapshotStateEnum::HISTORIC,
                'published_until' => now()
            ]);
        }


        /** @var Snapshot $snapshot */
        $snapshot = StoreWebsiteSnapshot::run(
            $portfolioWebsite,
            [
                'state'          => SnapshotStateEnum::LIVE,
                'published_at'   => now(),
                'layout'         => $layout,
                'scope'          => $marginal,
                'first_commit'   => $firstCommit,
                'comment'        => Arr::get($modelData, 'comment'),
                'publisher_id'   => Arr::get($modelData, 'publisher_id'),
                'publisher_type' => Arr::get($modelData, 'publisher_type'),
            ],
        );

        StoreDeployment::run(
            $portfolioWebsite,
            [
                'scope'          => $marginal,
                'snapshot_id'    => $snapshot->id,
                'publisher_id'   => Arr::get($modelData, 'publisher_id'),
                'publisher_type' => Arr::get($modelData, 'publisher_type'),
            ]
        );

        if (in_array($marginal, ['header', 'footer'])) {
            $updateData = [
                "live_{$marginal}_snapshot_id"    => $snapshot->id,
                "compiled_layout->$marginal"      => $snapshot->layout,
                "published_{$marginal}_checksum"  => md5(json_encode($snapshot->layout)),
            ];

            if ($marginal === 'footer' && $portfolioWebsite->footer_status && Arr::exists($portfolioWebsite->customer->integration_data, 'account')) {
                DeployPortfolioWebsiteFooterToAurora::run($portfolioWebsite, $snapshot->layout);
            }

        } else {
            $updateData = [
                "compiled_layout->$marginal"     => $snapshot->layout
            ];
        }

        $portfolioWebsite->update($updateData);

        BroadcastPreviewHeaderFooter::dispatch($portfolioWebsite);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'publisher_id'   => $request->user()->id,
                'publisher_type' => 'OrganisationUser'
            ]
        );
    }

    public function rules(): array
    {
        return [
            'comment'        => ['sometimes', 'required', 'string', 'max:1024'],
            'publisher_id'   => ['sometimes'],
            'publisher_type' => ['sometimes', 'string'],
            'layout'         => ['sometimes', 'array'],
        ];
    }

    public function header(PortfolioWebsite $portfolioWebsite, ActionRequest $request): string
    {
        $request->validate();

        $this->handle($portfolioWebsite, 'header', $request->validated());

        return "🚀";
    }

    public function footer(PortfolioWebsite $portfolioWebsite, ActionRequest $request): string
    {
        $this->isAction = true;
        $request->validate();
        $this->handle($portfolioWebsite, 'footer', $request->validated());

        return "🚀";
    }

    public function action(PortfolioWebsite $portfolioWebsite, $marginal, $modelData): string
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        $this->handle($portfolioWebsite, $marginal, $validatedData);

        return "🚀";
    }
}
