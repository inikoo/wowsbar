<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:01:05 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateBanners;
use App\Actions\Tenant\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Tenant\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Tenant\Portfolio\Slide\StoreSlide;
use App\Actions\Tenant\Portfolio\Slide\UpdateSlide;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Slide;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateBanner
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Banner $contentBlock, array $modelData): Banner
    {


        if(Arr::has($modelData, 'layout')) {
            $layout                                =Arr::pull($modelData, 'layout');
            list($layout, $contentBlockComponents) = ParseBannerLayout::run($layout, $contentBlock->webBlock);
            data_set($modelData, 'layout', $layout);

            if ($contentBlockComponents) {
                foreach ($contentBlockComponents as $ulid=>$contentBlockComponentData) {

                    $contentBlockComponent=Slide::where('ulid', $ulid)->first();
                    if($contentBlockComponent) {



                        UpdateSlide::run(
                            $contentBlockComponent,
                            Arr::only($contentBlockComponentData, ['layout','imageData'])
                        );
                    } else {
                        data_set($contentBlockComponent, 'ulid', $ulid);
                        StoreSlide::run(
                            contentBlock: $contentBlock,
                            modelData: $contentBlockComponentData,
                        );
                    }
                    /*

                    */
                }
            }

        }

        $this->update($contentBlock, $modelData, ['data','layout']);

        BannerHydrateUniversalSearch::dispatch($contentBlock);
        TenantHydrateBanners::dispatch(app('currentTenant'));

        return $contentBlock;
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
            'code'   => ['sometimes', 'required', 'unique:tenant.portfolio_websites', 'max:8'],
            'name'   => ['sometimes', 'required'],
            'layout' => ['sometimes', 'required','array:delay,common,components']
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
        return $this->handle($banner, $request->validated());
    }

    public function action(Banner $contentBlock, $modelData): Banner
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($contentBlock, $validatedData);
    }

    public function jsonResponse(Banner $website): BannerResource
    {
        return new BannerResource($website);
    }
}
