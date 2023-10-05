<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 15:43:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\HumanResources\JobPosition\StoreJobPosition;
use App\Actions\HumanResources\JobPosition\UpdateJobPosition;
use App\Models\Auth\Role;
use App\Models\HumanResources\JobPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class JobPositionSeeder extends Seeder
{
    public function run(): void
    {
        $jobPositions = collect(config("blueprint.job_positions.positions"));


        foreach ($jobPositions as $jobPositionData) {
            $jobPosition = JobPosition::where('code', $jobPositionData['code'])->first();
            if ($jobPosition) {
                UpdateJobPosition::run(
                    $jobPosition,
                    [
                        'name'       => $jobPositionData['name'],
                        'department' => Arr::get($jobPositionData, 'department'),
                        'team'       => Arr::get($jobPositionData, 'team'),
                    ]
                );
            } else {
                $jobPosition= StoreJobPosition::run(
                    [
                        'code'       => $jobPositionData['code'],
                        'name'       => $jobPositionData['name'],
                        'department' => Arr::get($jobPositionData, 'department'),
                        'team'       => Arr::get($jobPositionData, 'team'),
                    ],
                );
            }

            $roles = [];
            foreach ($jobPositionData['roles'] as $roleName) {
                if ($role = (new Role())->where('name', $roleName)->where('guard_name','org')->first()) {
                    $roles[] = $role->id;
                }
            }

            $jobPosition->roles()->sync($roles);
        }
    }
}
