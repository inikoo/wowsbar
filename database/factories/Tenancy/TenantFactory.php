<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 11:18:35 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\Tenancy;

use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use Illuminate\Database\Eloquent\Factories\Factory;


class TenantFactory extends Factory
{

    public function definition(): array
    {
        return [
            'code'         => 'ABC',
            'name'         => fake()->company(),
            'email'        => fake()->email(),
            'contact_name' => fake()->name(),
            'currency_id'  => Currency::where('code', 'GBP')->firstOrFail()->id,
            'country_id'   => Country::where('iso3', 'GBR')->firstOrFail()->id,
            'language_id'  => Language::where('code', 'en')->firstOrFail()->id,
            'timezone_id'  => Timezone::where('name', 'Europe/london')->firstOrFail()->id,

            'username' => 'abc',
            'password' => 'password'

        ];
    }
}
