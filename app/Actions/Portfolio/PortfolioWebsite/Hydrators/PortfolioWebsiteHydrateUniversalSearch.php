<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\Hydrators;

use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateUniversalSearch
{
    use AsAction;

    public function handle(PortfolioWebsite $website): void
    {
        $website->universalSearch()->create(
            [
                'section'     => 'portfolio',
                'title'       => trim($website->code.' '.$website->name),
                'description' => $website->domain
            ]
        );
    }

}
