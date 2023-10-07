<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateJobPositionsShare;
use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateWeekWorkingHours;
use App\Models\HumanResources\Employee;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class HydrateEmployee
{
    use asAction;


    public function handle(Employee $employee): void
    {
        EmployeeHydrateJobPositionsShare::run($employee);
        EmployeeHydrateWeekWorkingHours::run($employee);
    }


    public string $commandSignature = 'hydrate:employees {employees?*}';

    public function asCommand(Command $command): int
    {

        if(!$command->argument('employees')) {
            $employees=Employee::all();
        } else {
            $employees =  Employee::query()
                ->when($command->argument('employees'), function ($query) use ($command) {
                    $query->whereIn('slug', $command->argument('employees'));
                })
                ->cursor();
        }


        $exitCode = 0;

        foreach ($employees as $employee) {

            $this->handle($employee);
            $command->line("Employee {$employee->contact_name} hydrated 💦");

        }

        return $exitCode;
    }


}
