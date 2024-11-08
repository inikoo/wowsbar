<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class DeployPortfolioWebsiteFooterToAurora
{
    use WithActionUpdate;

    public function handle(PortfolioWebsite $portfolioWebsite, array $footer = null): Response
    {
        $key     = config('services.aurora.api_key');
        $website = parse_url($portfolioWebsite->url, PHP_URL_HOST);
        $account = Arr::get($portfolioWebsite->customer->integration_data, 'account');

        return Http::post("https://$account.aurora.systems/wowsbar_api.php?key=$key&website=$website", [
            'footer' => $footer
        ]);
    }
}
