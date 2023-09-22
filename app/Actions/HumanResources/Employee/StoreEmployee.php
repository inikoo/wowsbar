<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateWeekWorkingHours;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateEmployees;
use App\Models\HumanResources\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreEmployee
{
    use AsAction;
    use WithAttributes;

    public function handle(array $modelData): Employee
    {
        $employee = Employee::create($modelData);
        EmployeeHydrateWeekWorkingHours::run($employee);
        OrganisationHydrateEmployees::dispatch();

        //        EmployeeHydrateUniversalSearch::dispatch($employee);
        return $employee;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }


    public function rules(): array
    {
        return [
            'contact_name'      => ['required', 'max:255'],
            'date_of_birth'     => ['nullable', 'date', 'before_or_equal:today'],
            'job_title'         => ['sometimes','required'],
            'state'             => ['sometimes','required'],
            'email'             => ['sometimes','required', 'email'],
        ];
    }

    public function asController(ActionRequest $request): Employee
    {
        $request->validate();

        return $this->handle($request->validated());
    }

    public function htmlResponse(Employee $employee): RedirectResponse
    {
        return Redirect::route('org.hr.employees.show', $employee->slug);
    }
}