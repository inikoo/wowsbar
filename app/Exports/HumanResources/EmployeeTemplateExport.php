<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\HumanResources;

use App\Models\HumanResources\JobPosition;
use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class EmployeeTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        $array = [
              [
                  'Worker Number',
                  'Name',
                  'Alias',
                  'Job Title',
                  'Positions',
                  'Starting Date',
                  'Workplace',
                  'Username',
                  'password',
                  'reset password'
              ]
        ];

        do {
            $array[] = [
                rand(0000, 9999),
                fake()->name,
                fake()->userName,
                fake()->jobTitle,
                'dev-w',
                fake()->date,
                'bb',
                fake()->userName,
                fake()->password,
                fake()->boolean
            ];
        } while(count($array) <= 10);

        return $array;
    }
}
