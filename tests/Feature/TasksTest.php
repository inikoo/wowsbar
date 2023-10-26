<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 14:21:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\HumanResources\Employee\CreateOrganisationUserFromEmployee;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Task\Task\StoreTask;
use App\Actions\Task\TaskActivity\StoreTaskActivity;
use App\Actions\Task\TaskType\StoreTaskType;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\Organisation\Organisation;
use App\Models\Task\Task;
use App\Models\Task\TaskActivity;
use App\Models\Task\TaskType;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('create org', function () {
    return StoreOrganisation::make()->action(Organisation::factory()->definition());
});

//beforeEach(function () {
//    $arrayData = [
//        'alias'             => 'artha',
//        'contact_name'      => 'artha',
//        'date_of_birth'     => '2019-01-01',
//        'job_title'         => 'director',
//        'state'             => EmployeeStateEnum::WORKING,
//        'positions'         => ['acc-m']
//    ];
//
//    $employee = StoreEmployee::run(organisation(), $arrayData);
//    CreateOrganisationUserFromEmployee::run($employee);
//});

test('create task type', function () {
    $modelData = [
        'name' => 'Upload Post'
    ];

    $taskType = StoreTaskType::make()->action($modelData);

    expect($taskType)->toBeInstanceOf(TaskType::class);

    return $taskType;
});

test('create task', function ($taskType) {
    $modelData = [
        'date' => now()
    ];

    $task = StoreTask::make()->action(organisation()->users->first(), $taskType, $modelData);

    expect($task)->toBeInstanceOf(Task::class);
})->depends('create task type')->todo();

test('create task activities', function ($task) {
    $modelData = [
        'date' => now()
    ];

    $taskActivity = StoreTaskActivity::make()->action($task, $modelData);

    expect($taskActivity)->toBeInstanceOf(TaskActivity::class);
})->depends('create task');
