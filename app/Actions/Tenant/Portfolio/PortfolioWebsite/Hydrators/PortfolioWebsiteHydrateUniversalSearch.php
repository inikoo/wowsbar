<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators;

use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateUniversalSearch
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        $portfolioWebsite->universalSearch()->create(
            [
                'section'     => 'portfolio',
                'title'       => trim($portfolioWebsite->code.' '.$portfolioWebsite->name),
                'description' => $portfolioWebsite->domain
            ]
        );
    }

}
