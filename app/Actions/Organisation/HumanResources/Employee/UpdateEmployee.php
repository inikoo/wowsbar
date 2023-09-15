<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 26 Aug 2022 00:49:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Actions\Organisation\HumanResources\Employee;

use App\Actions\Organisation\HumanResources\Employee\Hydrators\EmployeeHydrateUniversalSearch;
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
