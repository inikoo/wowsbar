<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 19 Oct 2022 18:36:28 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources\Employee;

use App\Actions\HydrateModel;
use App\Actions\Organisation\HumanResources\Employee\Hydrators\EmployeeHydrateJobPositionsShare;
use App\Actions\Organisation\HumanResources\Employee\Hydrators\EmployeeHydrateWeekWorkingHours;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Collection;

class HydrateEmployee extends HydrateModel
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
