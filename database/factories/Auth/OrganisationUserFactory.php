<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 00:23:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\Auth;

use App\Models\Assets\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganisationUserFactory extends Factory
{
    public function definition(): array
    {
        /** @var \App\Models\Assets\Language $language */
        $language = Language::inRandomOrder()->first();

        return [
            'status'       => true,
            'contact_name' => fake()->name,
            'username'     => fake()->userName,
            'email'        => fake()->email,
            'password'     => 'password',
            'language_id'  => $language->id,
        ];
    }
}
