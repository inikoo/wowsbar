<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:34:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebpage;

use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Portfolio\PortfolioWebpage;
use Lorisleiva\Actions\Concerns\AsAction;

class CrawlPortfolioWebpage
{
    use AsAction;

    public function handle(PortfolioWebpage $portfolioWebpage): void
    {
        switch ($portfolioWebpage->portfolioWebsite->integration) {
            case PortfolioWebsiteIntegrationEnum::AURORA:
                CrawlAuroraPortfolioWebpage::run($portfolioWebpage);
                break;

        }
    }




}
