<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 01:13:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Portfolio\Crawl\StoreCrawl;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Portfolio\Crawl;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Mockery\Exception;

class CrawlPortfolioWebsite
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): Crawl
    {
        $crawl=StoreCrawl::make()->action($portfolioWebsite);


        switch ($portfolioWebsite->integration) {
            case PortfolioWebsiteIntegrationEnum::AURORA:
                CrawlAuroraPortfolioWebsite::make()->crawlAuroraWebsite($portfolioWebsite, $crawl);
                break;
        }
        $crawl->update(
            [
                'crawled_at' => now()
            ]
        );

        return $crawl;

    }

    public string $commandSignature = 'portfolio-website:crawl {portfolio_website}';

    public function asCommand(Command $command): int
    {
        try {
            $portfolioWebsite = PortfolioWebsite::where('slug', $command->argument('portfolio_website'))->firstOrFail();
        } catch (Exception) {
            $command->error("Portfolio website not found");

            return 1;
        }
        $crawl=$this->handle($portfolioWebsite);


        $command->table(
            [
                'Website',
                'Crawled Webpages',
                'New Webpages',
                'Updated Webpages'
            ],
            [
                [
                    $crawl->portfolioWebsite->url,
                    $crawl->number_of_crawled_webpages,
                    $crawl->number_of_new_webpages,
                    $crawl->number_of_updated_webpages
                ]
            ]
        );

        return 0;
    }
}
