<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Crawl;

use App\Actions\Portfolio\PortfolioWebsite\CrawlerPortfolioWebsite;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreCrawl
{
    use WithAttributes;
    use AsCommand;
    public function handle(PortfolioWebsite $portfolioWebsite, $modelData): Model
    {
        $crawls = $portfolioWebsite->crawlers()->create($modelData);
        CrawlerPortfolioWebsite::run($portfolioWebsite->url);

        return $crawls;
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Model
    {
        return $this->handle($portfolioWebsite, $request->all());
    }
}
