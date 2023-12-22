<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:37:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebpage;

use App\Models\Portfolio\PortfolioWebpage;
use App\Services\AuroraService;
use App\Services\SourceService;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class CrawlAuroraPortfolioWebpage
{
    use AsAction;

    public function handle(PortfolioWebpage $portfolioWebpage, SourceService $source): void
    {

    }


    public function crawlAuroraWebpage(PortfolioWebpage $portfolioWebpage): void
    {
        $source=new AuroraService();
        $source->initialisation(Arr::get($portfolioWebpage->portfolioWebsite->data, 'settings.db'));

    }


}
