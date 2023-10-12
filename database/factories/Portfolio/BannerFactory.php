<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 12:06:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\Portfolio;

use App\Enums\Portfolio\Banner\BannerTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'   => fake()->company(),
            'type'   => BannerTypeEnum::LANDSCAPE->value
        ];
    }
}
