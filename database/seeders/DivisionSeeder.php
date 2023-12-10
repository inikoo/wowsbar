<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 20:56:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\SysAdmin\Division\StoreDivision;
use App\Enums\Divisions\DivisionEnum;
use App\Models\SysAdmin\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisionsData = collect(DivisionEnum::values());

        $divisionsData->each(function ($slug) {

            $division=Division::where('slug', $slug)->first();

            if(!$division) {

                $modelData = [
                    'slug' => $slug,
                    'name' => Str::ucfirst($slug),
                ];

                StoreDivision::run($modelData);
            }

        });
    }
}
