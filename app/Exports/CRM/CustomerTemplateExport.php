<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\CRM;

use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class CustomerTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        $array = [
            ['Shop', 'Contact Name', 'Company Name', 'Email', 'Phone', 'Contact Website']
        ];

        do {
            $array[] = [
                'awa',
                fake()->name,
                fake()->company,
                fake()->email,
                fake()->phoneNumber,
                fake()->domainName
            ];
        } while(count($array) <= 5);

        return $array;
    }
}
