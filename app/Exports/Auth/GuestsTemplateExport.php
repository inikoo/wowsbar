<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Auth;

use App\Enums\Organisation\Guest\GuestTypeEnum;
use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class GuestsTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        $array = [
            ['Type', 'Username', 'Contact Name', 'Company Name', 'Phone', 'Email']
        ];

        do {
            $array[] = [
                GuestTypeEnum::CONTRACTOR->value,
                fake()->lexify,
                fake()->name,
                fake()->company,
                fake()->phoneNumber,
                fake()->email
            ];
        } while(count($array) <= 5);

        return $array;
    }
}
