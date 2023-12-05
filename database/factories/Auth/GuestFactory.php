<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 01:26:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuestFactory extends Factory
{
    public function definition(): array
    {

        $alias=fake()->lexify('????????');

        return [
            'alias'        => $alias,
            'contact_name' => fake()->name,
            'username'     => $alias,
            'email'        => fake()->email,
            'password'     => 'password',
        ];
    }
}
