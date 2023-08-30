<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:01:05 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateBanners;
use App\Actions\Tenant\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use Lorisleiva\Actions\ActionRequest;

class UpdateBanner
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Banner $banner, array $modelData): Banner
    {


        $this->update($banner, $modelData, ['data']);

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
            'code'                 => ['sometimes', 'required', 'unique:tenant.portfolio_websites', 'max:8'],
            'name'                 => ['sometimes', 'required'],
            'portfolio_website_id' => ['nullable']
        ];
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

    public function jsonResponse(Banner $website): BannerResource
    {
        return new BannerResource($website);
    }
}
