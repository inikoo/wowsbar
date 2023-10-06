<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateBanners;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteBanner
{
    use AsAction;
    use AsController;
    use WithAttributes;

    public bool $isAction                 = false;
    public PortfolioWebsite|null $website = null;

    public function handle(Banner $banner): Banner
    {
        $banner->delete();

        CustomerHydrateBanners::dispatch(customer());

        if(class_basename($banner->portfolioWebsite) == 'PortfolioWebsite') {
            PortfolioWebsiteHydrateBanners::dispatch($banner->portfolioWebsite);
        }

        // DeleteBannerElasticsearch::run($banner);

        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->isAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function action(Banner $banner): Banner
    {
        return $this->handle($banner);
    }

    public function asController(Banner $banner, ActionRequest $request): Banner
    {
        $request->validate();
        return $this->handle($banner);
    }


    public function htmlResponse(): RedirectResponse
    {
        return back();
    }
}
