<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 12:50:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Helpers\Query\Seeders\ShopScopeQuerySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            CountrySeeder::class,
            CurrencySeeder::class,
            TimezoneSeeder::class,
            LanguageSeeder::class,
            PaymentServiceProviderSeeder::class,
            ShopScopeQuerySeeder::class
        ]);
    }
}
