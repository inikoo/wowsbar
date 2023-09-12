<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 21 Jun 2023 07:56:25 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\CRM;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'contact_name'             => fake()->name,
            'company_name'             => fake()->company,
            'email'                    => fake()->email,
            'identity_document_number' => fake('en_GB')->vat(),
            'website'                  => fake()->url,
        ];
    }
}
