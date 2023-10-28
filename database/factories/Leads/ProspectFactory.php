<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 28 Oct 2023 11:16:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\Leads;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProspectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'contact_name'    => fake()->name,
            'company_name'    => fake()->company,
            'email'           => fake()->email,
            'contact_website' => fake()->url,
        ];
    }
}
