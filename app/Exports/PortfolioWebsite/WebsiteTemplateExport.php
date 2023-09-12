<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\PortfolioWebsite;

use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class WebsiteTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        $array = [
            ['Code', 'Name', 'Domain']
        ];

        do {
            $array[] = [
                fake()->lexify,
                fake()->name,
                fake()->domainName,
            ];
        } while(count($array) <= 5);

        return $array;
    }
}
