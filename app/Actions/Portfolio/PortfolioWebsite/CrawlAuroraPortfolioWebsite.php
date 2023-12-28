<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 01:13:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Portfolio\PortfolioWebpage\StorePortfolioWebpage;
use App\Actions\Portfolio\PortfolioWebpage\UpdatePortfolioWebpage;
use App\Models\Portfolio\Crawl;
use App\Models\Portfolio\PortfolioWebpage;
use App\Models\Portfolio\PortfolioWebsite;
use App\Services\AuroraService;
use App\Services\SourceService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CrawlAuroraPortfolioWebsite
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite, SourceService $source, Crawl $crawl): void
    {
        $query = DB::connection('aurora')
            ->table('Page Store Dimension')
            ->select('Page Key as source_id')
            ->where('Webpage Website Key', Arr::get($portfolioWebsite->integration_data, 'settings.website'))
            ->where('Webpage State', 'Online')
            ->orderBy('Page Key');


        $number_of_crawled_webpages = 0;
        $number_of_new_webpages     = 0;
        $number_of_updated_webpages = 0;

        $query->chunk(10000, function ($chunkedData) use (
            $source,
            $portfolioWebsite,
            $crawl,
            &$number_of_crawled_webpages,
            &$number_of_new_webpages,
            &$number_of_updated_webpages
        ) {
            foreach ($chunkedData as $auroraData) {
                $webpageData = $source->fetchWebpage($auroraData->source_id);
                $number_of_crawled_webpages++;



                /** @var PortfolioWebpage $portfolioWebpage */
                if($portfolioWebpage= $portfolioWebsite->portfolioWebpages()->where('source_slug', $auroraData->source_id)->first()) {
                    UpdatePortfolioWebpage::make()->action($portfolioWebpage, $webpageData);
                } else {
                    data_set($webpageData, 'source_slug', $auroraData->source_id);
                    //print_r($webpageData);
                    StorePortfolioWebpage::make()->action($portfolioWebsite, $webpageData);
                }


                $portfolioWebpage = $portfolioWebsite->portfolioWebpages()->updateOrCreate(
                    [
                        'source_slug' => $auroraData->source_id
                    ],
                    $webpageData
                );

                if ($portfolioWebpage->wasRecentlyCreated) {
                    $number_of_new_webpages++;
                }
                if ($portfolioWebpage->wasChanged()) {
                    $number_of_updated_webpages++;
                }
            }
        });
        $crawl->update(
            [
                'number_of_crawled_webpages' => $number_of_crawled_webpages,
                'number_of_new_webpages'     => $number_of_new_webpages,
                'number_of_updated_webpages' => $number_of_updated_webpages,
            ]
        );
    }

    public function crawlAuroraWebsite(PortfolioWebsite $portfolioWebsite, Crawl $crawl): void
    {
        $source = new AuroraService();
        $source->initialisation(Arr::get($portfolioWebsite->integration_data, 'settings.db'));
        $this->handle($portfolioWebsite, $source, $crawl);
    }


}
