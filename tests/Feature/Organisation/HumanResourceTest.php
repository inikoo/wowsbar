<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 16:23:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\HumanResources\ClockingMachine\StoreClockingMachine;
use App\Actions\HumanResources\ClockingMachine\UpdateClockingMachine;
use App\Actions\HumanResources\Employee\CreateOrganisationUserFromEmployee;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployeeWorkingHours;
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Actions\HumanResources\Workplace\UpdateWorkplace;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Models\Auth\OrganisationUser;
use App\Models\Helpers\Address;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Workplace;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\{get};
use function Pest\Laravel\{actingAs};

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    list(
        $this->organisation,
        $this->organisationUser,
        $this->shop
    ) = createShop();

    Config::set(
        'inertia.testing.page_paths',
        [resource_path('js/Pages/Organisation')]
    );
    actingAs($this->organisationUser, 'org');
});

test('check seeded job positions', function () {
    expect(organisation()->humanResourcesStats->number_job_positions)->toBe(19);
});

test('create employee successful', function () {
    $arrayData = [
        'alias'               => 'artha',
        'contact_name'        => 'artha',
        'employment_start_at' => '2019-01-01',
        'date_of_birth'       => '2000-01-01',
        'job_title'           => 'director',
        'state'               => EmployeeStateEnum::WORKING,
        'positions'           => ['acc-m'],
        'worker_number'       => '1234567890',
        'work_email'          => null,
        'email'               => null,
        'username'            => null
    ];

    $employee = StoreEmployee::make()->action(organisation(), $arrayData);


    expect($employee)->toBeInstanceOf(Employee::class)
        ->and(organisation()->humanResourcesStats->number_employees)->toBe(1)
        ->and(organisation()->humanResourcesStats->number_employees_type_employee)->toBe(1)
        ->and(organisation()->humanResourcesStats->number_employees_state_working)->toBe(1);

    return $employee;
});

test('update employees successful', function ($lastEmployee) {
    $arrayData = [
        'contact_name'  => 'vica',
        'date_of_birth' => '2019-01-01',
        'job_title'     => 'director',
    ];

    $updatedEmployee = UpdateEmployee::run($lastEmployee, $arrayData);

    expect($updatedEmployee->contact_name)->toBe($arrayData['contact_name']);
})->depends('create employee successful');

test('update employee working hours', function () {
    $lastEmployee    = Employee::latest()->first();
    $updatedEmployee = UpdateEmployeeWorkingHours::run($lastEmployee, [10]);
    expect($updatedEmployee['working_hours'])->toBeArray(10);
});

test('create user from employee', function () {
    $lastEmployee = Employee::latest()->first();
    expect($lastEmployee)->toBeInstanceOf(Employee::class);
    $organisationUser = CreateOrganisationUserFromEmployee::run($lastEmployee);
    expect($organisationUser)->toBeInstanceOf(OrganisationUser::class)
        ->and($organisationUser->contact_name)->toBe($lastEmployee->contact_name);
});

test('create working place successful', function () {
    $modelData = [
        'name'    => 'office',
        'type'    => WorkplaceTypeEnum::BRANCH,
        'address' => Address::factory()->definition()
    ];

    $workplace = StoreWorkplace::make()->action($modelData);
    expect($workplace)->toBeInstanceOf(Workplace::class)
        ->and(organisation()->humanResourcesStats->number_workplaces)->toBe(1)
        ->and(organisation()->humanResourcesStats->number_workplaces_type_branch)->toBe(1);


    return $workplace;
});

test('update working place successful', function ($createdWorkplace) {
    $arrayData        = [
        'name'    => 'home office',
        'type'    => 'home',
        'address' => Address::create(Address::factory()->definition())->toArray()
    ];
    $updatedWorkplace = UpdateWorkplace::run($createdWorkplace, $arrayData);

    expect($updatedWorkplace->name)->toBe($arrayData['name'])
        ->and(organisation()->humanResourcesStats->number_workplaces_type_branch)->toBe(0)
        ->and(organisation()->humanResourcesStats->number_workplaces_type_home)->toBe(1);
})->depends('create working place successful');

test('create working place by command', function () {
    $this->artisan('workplace:create office2 hq')->assertExitCode(0);
    $this->artisan('workplace:create office2 hq')->assertExitCode(1);
    $workplace=Workplace::where('name', 'office2')->first();
    $this->organisation->refresh();
    expect($workplace)->not->toBeNull()
        ->and($this->organisation->humanResourcesStats->number_workplaces)->toBe(2);
});



test('create clocking machines', function ($createdWorkplace) {
    $arrayData = [
        'code' => 'ABC'
    ];

    $clockingMachine = StoreClockingMachine::run($createdWorkplace, $arrayData);
    expect($clockingMachine->code)->toBe($arrayData['code']);

    return $clockingMachine;
})->depends('create working place successful');


test('update clocking machines', function ($createdClockingMachine) {
    $arrayData = [
        'code' => 'abc',
    ];

    $updatedClockingMachine = UpdateClockingMachine::run($createdClockingMachine, $arrayData);

    expect($updatedClockingMachine->code)->toBe($arrayData['code']);
})->depends('create clocking machines');

test('can show hr dashboard', function () {
    $response = get(route('org.hr.dashboard'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('HumanResources/HumanResourcesDashboard')
            ->has('breadcrumbs', 2)
            ->where('stats.0.stat', 1)->where('stats.0.href.name', 'org.hr.employees.index')
            ->where('stats.1.stat', 2)->where('stats.1.href.name', 'org.hr.workplaces.index');
    });
});

test('can show list of workplaces', function () {
    $response = get(route('org.hr.workplaces.index'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('HumanResources/Workplaces')
            ->has('title')
            ->has('breadcrumbs', 3)
            ->has('data.data', 2);
    });
});

test('can show workplace', function () {
    $workplace = Workplace::first();
    $response  = get(route('org.hr.workplaces.show', [$workplace->slug]));

    $response->assertInertia(function (AssertableInertia $page) use ($workplace) {
        $page
            ->component('HumanResources/Workplace')
            ->has('breadcrumbs', 3)
            ->where('pageHead.meta.0.href.name', 'org.hr.workplaces.show.clocking-machines.index')
            ->where('pageHead.meta.0.href.parameters', $workplace->slug)
            ->has('tabs.navigation', 5);
    });
});

test('can show list of employees', function () {
    $response = get(route('org.hr.employees.index'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('HumanResources/Employees')
            ->has('title')
            ->has('breadcrumbs', 3)
            ->has('data.data', 1);
    });
});

test('can show employees', function () {
    $employee = Employee::first();
    expect($employee->organisationUser)->toBeInstanceOf(OrganisationUser::class);

    $response = get(route('org.hr.employees.show', [$employee->slug]));

    $response->assertInertia(function (AssertableInertia $page) use ($employee) {
        $page
            ->component('HumanResources/Employee')
            ->has('breadcrumbs', 3)
            ->where('pageHead.meta.1.href.name', 'org.sysadmin.users.show')
            ->where('pageHead.meta.1.href.parameters', $employee->alias)
            ->has('tabs.navigation', 7);
    });
});
