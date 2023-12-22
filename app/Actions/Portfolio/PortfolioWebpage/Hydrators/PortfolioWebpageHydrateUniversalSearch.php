<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:05:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebpage\Hydrators;

use App\Models\Portfolio\PortfolioWebpage;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebpageHydrateUniversalSearch
{
    use AsAction;

    public function handle(PortfolioWebpage $portfolioWebpage): void
    {
        $portfolioWebpage->universalSearch()->create(
            [
                'in_organisation' => false,
                'shop_id'         => $portfolioWebpage->portfolioWebsite->customer->shop_id,
                'webpage_id'      => $portfolioWebpage->portfolioWebsite->customer->webpage_id,
                'customer_id'     => $portfolioWebpage->portfolioWebsite->customer_id,
                'section'         => 'portfolio',
                'title'           => join(' ', [$portfolioWebpage->slug,$portfolioWebpage->name,$portfolioWebpage->url]),
                'description'     => null
            ]
        );
    }

}
