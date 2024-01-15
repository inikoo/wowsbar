<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 24 Dec 2023 21:29:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\HydrateModel;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateProspects;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateWebpages;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Collection;

class HydratePortfolioWebsite extends HydrateModel
{
    public string $commandSignature = 'portfolio-website:hydrate {slugs?*} ';

    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        PortfolioWebsiteHydrateBanners::run($portfolioWebsite);
        PortfolioWebsiteHydrateWebpages::run($portfolioWebsite);
        PortfolioWebsiteHydrateProspects::run($portfolioWebsite);
    }

    protected function getModel(string $slug): PortfolioWebsite
    {
        return PortfolioWebsite::firstWhere($slug);
    }

    protected function getAllModels(): Collection
    {
        return PortfolioWebsite::get();
    }

}
