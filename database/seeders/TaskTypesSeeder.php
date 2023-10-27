<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 20:56:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Tasks\TaskType\StoreTaskType;
use App\Models\Organisation\Division;
use App\Models\Tasks\TaskType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class TaskTypesSeeder extends Seeder
{
    public function run(): void
    {
        $taskTypesData = collect(
            json_decode(
                Storage::disk('datasets')->get('task-types.json'),
                true
            )
        );

        $taskTypesData->each(function ($modelData) {
            $division = Division::where('slug', Arr::get($modelData, 'division_slug'))->first();
            Arr::forget($modelData, 'division_slug');
            if (!TaskType::where('name', Arr::get($modelData, 'name'))->count()) {
                StoreTaskType::run($division, $modelData);
            }
        });
    }
}
