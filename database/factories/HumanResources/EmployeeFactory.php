<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 01:26:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Factories\HumanResources;

use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {

        $alias=fake()->lexify('????????');

        return [
            'alias'               => $alias,
            'contact_name'        => fake()->name,
            'employment_start_at' => '2019-01-01',
            'date_of_birth'       => '2000-01-01',
            'job_title'           => 'director',
            'state'               => EmployeeStateEnum::WORKING,
            'positions'           => ['acc-m'],
            'worker_number'       => '1234567890',
            'work_email'          => null,
            'email'               => null,
            'username'            => null
        ];
    }
}
