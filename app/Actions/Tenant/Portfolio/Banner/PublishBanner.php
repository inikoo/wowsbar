<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 25 Aug 2023 11:43:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateBanners;
use App\Actions\Tenant\Portfolio\Banner\Elasticsearch\StoreBannerElasticsearch;
use App\Actions\Tenant\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Tenant\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Actions\Tenant\Portfolio\Snapshot\StoreSnapshot;
use App\Actions\Tenant\Portfolio\Snapshot\UpdateSnapshot;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class PublishBanner
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Banner $banner, array $modelData): Banner
    {



        foreach($banner->snapshots()->where('state', SnapshotStateEnum::LIVE)->get() as $liveSnapshot) {
            UpdateSnapshot::run($liveSnapshot, [
                'state'          => SnapshotStateEnum::HISTORIC,
                'published_until'=> now()
            ]);
        }


        $layout                = Arr::pull($modelData, 'layout');
        list($layout, $slides) = ParseBannerLayout::run($layout);

        $snapshot=StoreSnapshot::run(
            $banner,
            [
                'state'       => SnapshotStateEnum::LIVE,
                'published_at'=> now(),
                'layout'      => $layout
            ],
            $slides
        );


        $compiledLayout=$snapshot->compiledLayout();
        $updateData    =[
            'live_snapshot_id'=> $snapshot->id,
            'compiled_layout' => $compiledLayout,
            'state'           => BannerStateEnum::LIVE,
            'checksum'        => md5(json_encode($compiledLayout))
        ];

        if($banner->state==BannerStateEnum::UNPUBLISHED) {
            $updateData['live_at']=now();
        }

        $banner->update($updateData);
        StoreBannerElasticsearch::run($banner);
        BannerHydrateUniversalSearch::dispatch($banner);
        TenantHydrateBanners::dispatch(app('currentTenant'));

        if (class_basename($banner->portfolioWebsite) == 'PortfolioWebsite') {
            PortfolioWebsiteHydrateBanners::dispatch($banner->portfolioWebsite);
        }

        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'layout' => ['required', 'array:delay,common,components'],
            'comment'=>['sometimes','required','string','max:1024']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'layout' => $request->only(['delay', 'common', 'components']),
            ]
        );
    }

    public function asController(Banner $banner, ActionRequest $request): Banner
    {
        $request->validate();
        return $this->handle($banner, $request->validated());
    }

    public function action(Banner $banner, $modelData): Banner
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($banner, $validatedData);
    }

    public function jsonResponse(Banner $banner): BannerResource
    {
        return new BannerResource($banner);
    }

}
