<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\Hydrators;

use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateUniversalSearch
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        $portfolioWebsite->universalSearch()->create(
            [
                'shop_id'     => $portfolioWebsite->customer->shop_id,
                'website_id'  => $portfolioWebsite->customer->website_id,
                'customer_id' => $portfolioWebsite->customer_id,
                'section'     => 'portfolio',
                'title'       => trim($portfolioWebsite->code.' '.$portfolioWebsite->name),
                'description' => $portfolioWebsite->domain
            ]
        );
    }

}
