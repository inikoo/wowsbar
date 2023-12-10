<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\SysAdmin\OrganisationUser\StoreOrganisationUser;
use App\Models\Auth\OrganisationUser;
use App\Models\HumanResources\Employee;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

/**
 * @property string $newPassword
 */
class CreateOrganisationUserFromEmployee
{
    use AsAction;
    use WithAttributes;


    public string $commandSignature = 'create:user-from-employee  {employee : The employee identification code}';

    public function getCommandDescription(): string
    {
        return 'Create a user from an employee.';
    }


    public function handle(Employee $employee, ?string $password = null, ?string $organisationUsername = null): OrganisationUser
    {
        $this->newPassword = $password ?? (app()->isProduction() ? wordwrap(Str::random(), 4, '-', true) : 'hello');
        $modelData         = [
            'username'    => $organisationUsername ?? $employee->slug,
            'password'    => $this->newPassword,
            'contact_name'=> $employee->contact_name,
            'email'       => $employee->work_email,
        ];



        $organisationUser = StoreOrganisationUser::run($employee, $modelData);
        foreach ($employee->jobPositions as $jobPosition) {
            $organisationUser->assignJoBPositionRoles($jobPosition);
        }

        return $organisationUser;
    }

    public function asController(Employee $employee): OrganisationUser
    {
        if ($employee->organisationUser) {
            return $employee->organisationUser;
        }

        return $this->handle($employee);
    }

    public function HtmlResponse(OrganisationUser $organisationUser): RedirectResponse
    {
        /** @var \App\Models\HumanResources\Employee $employee */
        $employee = $organisationUser->parent;

        return Redirect::route('org.hr.employees.show', $employee->id)->with('notification', [
            'type'   => 'newUser',
            'message'=> __('New user created'),
            'fields' => [
                'username' => [
                    'label' => __('username'),
                    'value' => $organisationUser->username
                ],
                'password' => [
                    'label' => __('password'),
                    'value' => $this->newPassword
                ]
            ]

        ]);
    }

    public function asCommand(Command $command): int
    {
        $employee = Employee::where('code', $command->argument('employee'))->first();
        if (!$employee) {
            $command->error("Employee ".$command->argument('employee')." not found");

            return 1;
        }


        if ($employee->organisationUser) {
            $command->error("Employee already has an user");

            return 1;
        }


        $password             = (app()->isProduction() ? wordwrap(Str::random(), 4, '-', true) : 'hello');
        $organisationUser     = $this->handle($employee);


        $command->line("Employee user created $organisationUser->username");

        $command->table(
            ['Username', 'Password', 'Name', 'Roles'],
            [
                [
                    $organisationUser->username,
                    $password,
                    $employee->contact_name,
                    $organisationUser->getRoleNames()->implode(',')
                ],

            ]
        );

        return 0;
    }
}
