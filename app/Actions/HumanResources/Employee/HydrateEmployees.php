<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateJobPositionsShare;
use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateWeekWorkingHours;
use App\Actions\HydrateModel;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class HydrateEmployees extends HydrateModel
{
    use asAction;


    public function handle(Employee $employee): void
    {
        EmployeeHydrateJobPositionsShare::run($employee);
        EmployeeHydrateWeekWorkingHours::run($employee);
    }


    public string $commandSignature = 'hydrate:employees {slugs?*}';


    public function getAllModels(): \Illuminate\Support\Collection
    {
        return Employee::all();
    }

    public function getModel(string $slug): Employee
    {
        return Employee::firstWhere('slug', $slug);
    }

}
