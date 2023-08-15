<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateEmployees
{
    use AsAction;

    public function handle(): void
    {

        $stats = [
            'number_employees' => Employee::count()
        ];


        $employeeStateCount = Employee::selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();


        foreach (EmployeeStateEnum::cases() as $employeeState) {
            $stats['number_employees_state_'.$employeeState->snake()] = Arr::get($employeeStateCount, $employeeState->value, 0);
        }


        organisation()->stats->update($stats);
    }
}
