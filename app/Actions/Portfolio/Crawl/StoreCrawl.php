<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Crawl;

use App\Enums\Portfolio\Crawl\CrawlTypeEnum;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Portfolio\Crawl;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreCrawl
{
    use WithAttributes;
    use AsAction;

    private bool $asAction = false;

    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData = []): Crawl
    {
        data_set(
            $modelData,
            'type',
            match ($portfolioWebsite->integration) {
                PortfolioWebsiteIntegrationEnum::AURORA => CrawlTypeEnum::AURORA,
                PortfolioWebsiteIntegrationEnum::NONE   => CrawlTypeEnum::SPATIE_CRAWLER
            }
        );

        /** @var Crawl $crawl */
        $crawl = $portfolioWebsite->crawlers()->create($modelData);

        return $crawl;
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Model
    {
        return $this->handle($portfolioWebsite, $request->all());
    }

    public function action($portfolioWebsite): Crawl
    {
        $this->asAction = true;

        return $this->handle($portfolioWebsite);
    }

}
