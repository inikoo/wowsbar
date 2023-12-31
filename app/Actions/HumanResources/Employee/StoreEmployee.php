<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateUniversalSearch;
use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateWeekWorkingHours;
use App\Actions\HumanResources\SyncJobPosition;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateEmployees;
use App\Actions\SysAdmin\OrganisationUser\StoreOrganisationUser;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use App\Models\HumanResources\Workplace;
use App\Models\SysAdmin\Organisation;
use App\Rules\AlphaDashDot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreEmployee
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(Organisation|Workplace $parent, array $modelData): Employee
    {
        $positions = Arr::get($modelData, 'positions', []);

        $credentials = Arr::only($modelData, ['username', 'password', 'reset_password']);

        Arr::forget($modelData, 'positions');
        Arr::forget($modelData, ['username', 'password', 'reset_password']);

        $employee = match (class_basename($parent)) {
            'Workplace' => $parent->employees()->create($modelData),
            default     => Employee::create($modelData)
        };


        if (Arr::get($credentials, 'username')) {
            $organisationUserData = [
                'username'       => Arr::get($credentials, 'username'),
                'password'       => Arr::get(
                    $credentials,
                    'password',
                    (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
                ),
                'contact_name'   => $employee->contact_name,
                'email'          => $employee->work_email,
                'reset_password' => Arr::get($credentials, 'reset_password', false),
            ];

            if (Arr::get($employee->data, 'bulk_import')) {
                $organisationUserData['data'] = [
                    'bulk_import' => Arr::get($employee->data, 'bulk_import')
                ];
            }

            StoreOrganisationUser::make()->action(
                $employee,
                $organisationUserData
            );
        }


        $jobPositions = [];
        foreach ($positions as $position) {
            $jobPosition    = JobPosition::firstWhere('slug', $position);
            $jobPositions[] = $jobPosition->id;
        }
        SyncJobPosition::run($employee, $jobPositions);


        EmployeeHydrateWeekWorkingHours::dispatch($employee);
        OrganisationHydrateEmployees::dispatch();

        EmployeeHydrateUniversalSearch::dispatch($employee);

        return $employee;
    }

    public function authorize(
        ActionRequest $request
    ): bool {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("hr.edit");
    }


    public function action(
        Organisation|Workplace $parent,
        $objectData
    ): Employee {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }

    public function prepareForValidation(
        ActionRequest $request
    ): void {
        if ($request->get('username')) {
            $request->merge(['some' => 'additional data']);
        }
    }

    public function rules(): array
    {
        return [
            'worker_number'       => ['required', 'max:64', 'iunique:employees', 'alpha_dash:ascii'],
            'employment_start_at' => ['required', 'nullable', 'date'],
            'work_email'          => ['present', 'nullable', 'email', 'iunique:employees'],
            'alias'               => ['required', 'iunique:employees', 'string', 'max:12'],
            'contact_name'        => ['required', 'string', 'max:256'],
            'date_of_birth'       => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'job_title'           => ['required', 'string', 'max:256'],
            'state'               => ['required', new Enum(EmployeeStateEnum::class)],
            'positions'           => ['required', 'array'],
            'positions.*'         => ['exists:job_positions,slug'],
            'email'               => ['present', 'nullable', 'email'],
            'username'            => ['nullable', new AlphaDashDot(), 'iunique:organisation_users'],
            'password'            => ['exclude_if:username,null', 'required', 'max:255', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'reset_password'      => ['sometimes', 'boolean'],
            'data'                => ['sometimes', 'array'],
        ];
    }

    public function asController(
        ActionRequest $request
    ): Employee {
        $request->validate();

        return $this->handle(organisation(), $request->validated());
    }

    public function htmlResponse(
        Employee $employee
    ): RedirectResponse {
        return Redirect::route('org.hr.employees.show', $employee->slug);
    }
}
