<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 23 Aug 2023 13:23:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\Concerns\AsObject;

class GetPortfolioWebsitesOptions
{
    use AsObject;

    public function handle(): array
    {
        $selectOptions = [];

        foreach (PortfolioWebsite::all() as $portfolioWebsite) {
            $selectOptions[$portfolioWebsite->id] =
                [
                    'label' => $portfolioWebsite->name.' ('.$portfolioWebsite->code.')',
                ];
        }

        return $selectOptions;
    }
}
