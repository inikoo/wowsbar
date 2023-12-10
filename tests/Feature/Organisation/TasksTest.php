<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 14:21:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\SysAdmin\Organisation\StoreOrganisation;
use App\Models\SysAdmin\Organisation;
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

/*
test('create task', function ($taskType) {
    $modelData = [
        'date' => now()
    ];

    $task = StoreTask::make()->action(organisation()->users->first(), $taskType, $modelData);

    expect($task)->toBeInstanceOf(Tasks::class);
})->depends('create task type')->todo();

test('create task activities', function ($task) {
    $modelData = [
        'date' => now()
    ];

    $task = StoreTask::make()->action($task, $modelData);

    expect($task)->toBeInstanceOf(Tasks::class);
})->depends('create task')->todo();
*/
