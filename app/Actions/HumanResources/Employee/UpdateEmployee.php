<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\HumanResources\AttachJobPosition;
use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateUniversalSearch;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateEmployees;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;

class UpdateEmployee
{
    use WithActionUpdate;

    public function handle(Employee $employee, array $modelData): Employee
    {
        $positions = Arr::get($modelData, 'positions');
        Arr::forget($modelData, 'positions');

        $employee = $this->update($employee, $modelData, ['data', 'salary',]);

        if ($employee->wasChanged(['worker_number', 'worker_number', 'contact_name', 'work_email', 'job_title', 'email'])) {
            EmployeeHydrateUniversalSearch::dispatch($employee);
        }
        if ($employee->wasChanged(['state'])) {
            OrganisationHydrateEmployees::dispatch();
        }
        foreach ($positions as $position) {
            $jobPosition = JobPosition::firstWhere('slug', $position);
            AttachJobPosition::run($employee, $jobPosition);
        }

        return $employee;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function rules(): array
    {
        return [
            'worker_number'       => ['sometimes', 'required', 'max:64', 'iunique:employees', 'alpha_dash:ascii'],
            'employment_start_at' => ['sometimes', 'nullable', 'date'],
            'work_email'          => ['sometimes', 'nullable', 'email', 'iunique:employees'],
            'alias'               => ['sometimes', 'required', 'iunique:employees', 'string', 'max:12'],
            'contact_name'        => ['sometimes', 'required', 'max:256'],
            'date_of_birth'       => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'job_title'           => ['sometimes', 'required', 'max:256'],
            'state'               => ['sometimes', 'required', new Enum(EmployeeStateEnum::class)],
            'positions.*'         => ['sometimes', 'exists:job_positions,slug'],
            'email'               => ['sometimes', 'nullable', 'email'],
            'positions'           => ['sometimes', 'required', 'array'],
            'username'            => ['sometimes', 'required', 'iunique:organisation_users'],
            'password'            => ['sometimes', 'required', 'max:255', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],

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
