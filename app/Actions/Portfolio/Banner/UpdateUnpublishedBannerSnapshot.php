<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Actions\Portfolio\Slide\StoreSlide;
use App\Actions\Portfolio\Slide\UpdateSlide;
use App\Actions\Tenant\Portfolio\Banner\CustomerHydrateBanners;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Slide;
use App\Models\Portfolio\Snapshot;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateUnpublishedBannerSnapshot
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Snapshot $snapshot, array $modelData): Banner
    {


        $layout                       = Arr::pull($modelData, 'layout');
        list($layout, $slides, $hash) = ParseBannerLayout::run($layout);
        data_set($modelData, 'layout', $layout);
        data_set($modelData, 'checksum', $hash);

        if ($slides) {
            foreach ($slides as $ulid => $slideData) {
                $slide = Slide::where('ulid', $ulid)->first();
                if ($slide) {
                    UpdateSlide::run(
                        $slide,
                        Arr::only($slideData, ['layout', 'imageData'])
                    );
                } else {
                    data_set($slide, 'ulid', $ulid);
                    StoreSlide::run(
                        snapshot: $snapshot,
                        modelData: $slideData,
                    );
                }
            }
        }


        $this->update($snapshot, $modelData, ['layout']);

        /** @var Banner $banner */
        $banner = $snapshot->parent;
        BannerHydrateUniversalSearch::dispatch($banner);
        CustomerHydrateBanners::dispatch(customer());

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
            'layout' => ['sometimes', 'required', 'array:delay,common,components']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'layout' => $request->only(['delay', 'common', 'components'])
            ]
        );
    }

    public function asController(Banner $banner, ActionRequest $request): Banner
    {
        $request->validate();

        return $this->handle($banner->unpublishedSnapshot, $request->validated());
    }

    public function action(Banner $banner, $modelData): Banner
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($banner->unpublishedSnapshot, $validatedData);
    }

    public function jsonResponse(Banner $banner): BannerResource
    {
        return new BannerResource($banner);
    }
}