<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 12:06:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\Portfolio;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContentBlockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'   => fake()->lexify(),
            'name'   => fake()->company(),
        ];
    }
}
