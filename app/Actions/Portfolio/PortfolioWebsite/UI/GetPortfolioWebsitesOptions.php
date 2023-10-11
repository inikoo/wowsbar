<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
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
                    'label' => $portfolioWebsite->name.' ('.$portfolioWebsite->url.')',
                ];
        }

        return $selectOptions;
    }
}
