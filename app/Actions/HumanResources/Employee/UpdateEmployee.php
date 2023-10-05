<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\ActionRequest;

class UpdateEmployee
{
    use WithActionUpdate;

    public function handle(Employee $employee, array $modelData): Employee
    {
        $employee =  $this->update($employee, $modelData, ['data', 'salary',]);

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
            'worker_number'       => ['sometimes','required', 'max:64', 'iunique:employees', 'alpha_dash:ascii'],
            'employment_start_at' => ['sometimes', 'nullable', 'date'],

            'contact_name'  => ['sometimes','required'],
            'date_of_birth' => ['sometimes','date'],
            'job_title'     => ['sometimes','required'],
            'state'         => ['sometimes','required'],
        ];
    }

    public function asController(Employee $employee, ActionRequest $request): Employee
    {
        $request->validate();

        return $this->handle($employee, $request->validated());
    }

    public function jsonResponse(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }
}
