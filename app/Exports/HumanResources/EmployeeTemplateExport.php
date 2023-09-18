<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\HumanResources;

use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class EmployeeTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        $array = [
            ['Code', 'Contact Name', 'Date of Birth', 'Job Title', 'Email']
        ];

        do {
            $array[] = [
                fake()->lexify,
                fake()->name,
                fake()->date,
                fake()->jobTitle,
                fake()->email
            ];
        } while(count($array) <= 5);

        return $array;
    }
}
