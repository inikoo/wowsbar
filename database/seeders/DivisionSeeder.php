<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 20:56:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Organisation\Division\StoreDivision;
use App\Models\Organisation\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisionsData = collect(
            json_decode(
                Storage::disk('datasets')->get('divisions.json'),
                true
            )
        );

        $divisionsData->each(function ($modelData) {


            $division=Division::where('slug', Arr::get($modelData, 'slug'))->first();

            if(!$division) {
                StoreDivision::run($modelData);
            }


        });
    }
}
