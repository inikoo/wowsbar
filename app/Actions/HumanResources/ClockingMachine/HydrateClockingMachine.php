<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateJobPositionsShare;
use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateWeekWorkingHours;
use App\Actions\HydrateModel;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Collection;

class HydrateClockingMachine extends HydrateModel
{
    public string $commandSignature = 'hydrate:employee {tenants?*} {--i|id=}';


    public function handle(Employee $employee): void
    {
        EmployeeHydrateJobPositionsShare::run($employee);
        EmployeeHydrateWeekWorkingHours::run($employee);
    }


    protected function getModel(int $id): Employee
    {
        return Employee::findOrFail($id);
    }

    protected function getAllModels(): Collection
    {
        return Employee::get();
    }
}
