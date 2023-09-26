<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Market;

use App\Enums\Market\Product\ProductTypeEnum;
use App\Enums\Catalogue\Product\ProductStateEnum;
use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class ProductTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        $array = [
            ['Code', 'Unit', 'Price', 'RRP', 'Name', 'State', 'Type', 'Description']
        ];

        do {
            $array[] = [
                fake()->lexify,
                fake()->numerify,
                fake()->numerify,
                fake()->numerify,
                fake()->name,
                ProductStateEnum::ACTIVE->value,
                ProductTypeEnum::PHYSICAL_GOOD->value,
                fake()->text(1500)
            ];
        } while(count($array) <= 5);

        return $array;
    }
}
