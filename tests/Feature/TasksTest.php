<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 14:21:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Task\Task\StoreTask;
use App\Actions\Task\TaskActivity\StoreTaskActivity;
use App\Actions\Task\TaskType\StoreTaskType;
use App\Models\Organisation\Division;
use App\Models\Organisation\Organisation;
use App\Models\Task\Task;
use App\Models\Task\TaskActivity;
use App\Models\Task\TaskType;
use Illuminate\Support\Facades\Artisan;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    try {
        organisation();
    } catch (Exception) {
        StoreOrganisation::make()->action(Organisation::factory()->definition());
    }
});

test('check division and task types seeders', function () {
    expect(organisation()->taskStats->number_divisions)->toBe(5)
        ->and(organisation()->taskStats->number_task_types)->toBe(2);

    Artisan::call("db:seed --force --class=DivisionSeeder");
    Artisan::call("db:seed --force --class=TaskTypesSeeder");
    expect(organisation()->taskStats->number_divisions)->toBe(5)
        ->and(organisation()->taskStats->number_task_types)->toBe(2);
});

test('create task type', function () {
    $modelData = [
        'name' => 'Upload Post'
    ];

    $division = Division::firstWhere('slug', 'seo');
    $taskType = StoreTaskType::make()->action($division, $modelData);
    expect($taskType)->toBeInstanceOf(TaskType::class);

    return $taskType;
});

/*
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
})->depends('create task')->todo();
*/
