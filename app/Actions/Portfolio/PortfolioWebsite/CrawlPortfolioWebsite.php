<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 01:13:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Mockery\Exception;

class CrawlPortfolioWebsite
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        switch ($portfolioWebsite->integration) {
            case PortfolioWebsiteIntegrationEnum::AURORA:
                CrawlAuroraPortfolioWebsite::make()->crawlAuroraWebsite($portfolioWebsite);
                break;

        }
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
        $this->handle($portfolioWebsite);

        return 0;
    }


}
