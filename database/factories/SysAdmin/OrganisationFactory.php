<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:18:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\SysAdmin;

use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganisationFactory extends Factory
{
    public function definition(): array
    {
        $country  = Country::where('code', 'US')->firstOrFail();
        $language = Language::where('code', 'en')->firstOrFail();
        $timezone = Timezone::where('name', fake()->timezone('US'))->firstOrFail();
        $currency = Currency::where('code', 'USD')->firstOrFail();


        return [
            'code'         => fake()->lexify(),
            'name'         => fake()->company(),
            'country_id'   => $country->id,
            'language_id'  => $language->id,
            'timezone_id'  => $timezone->id,
            'currency_id'  => $currency->id,
        ];
    }
}
