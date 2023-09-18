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

    public function handle(Banner $contentBlock): Banner
    {
        $contentBlock->delete();

        CustomerHydrateBanners::dispatch(customer());

        if(class_basename($contentBlock->portfolioWebsite) == 'PortfolioWebsite') {
            PortfolioWebsiteHydrateBanners::dispatch($contentBlock->portfolioWebsite);
        }

        // DeleteBannerElasticsearch::run($contentBlock);

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->isAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function action(Banner $contentBlock): Banner
    {
        return $this->handle($contentBlock);
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
