<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:18:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\CRM;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [

            'email'        => fake()->email(),
            'contact_name' => fake()->name(),
            'company_name' => fake()->company(),


        ];
    }
}
