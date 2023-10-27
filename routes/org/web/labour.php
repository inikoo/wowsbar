<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:11:13 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Tasks\Division\UI\IndexDivisions;
use App\Actions\Tasks\Task\UI\IndexTasks;
use App\Actions\Tasks\TaskType\UI\IndexTaskTypes;
use App\Actions\UI\Organisation\Tasks\ShowTasksDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/sysadmin/dashboard');});
Route::get('/dashboard', ShowTasksDashboard::class)->name('dashboard');

Route::prefix('divisions')->as('divisions.')->group(function () {
    Route::get('/', IndexDivisions::class)->name('index');
    Route::get('/{taskType}', IndexTaskTypes::class)->name('show');
});

Route::prefix('types')->as('types.')->group(function () {
    Route::get('/', IndexTaskTypes::class)->name('index');
    Route::get('/{taskType}', IndexTaskTypes::class)->name('show');
});

Route::prefix('tasks')->as('tasks.')->group(function () {
    Route::get('/', IndexTasks::class)->name('index');
    Route::get('/{taskType}', IndexTasks::class)->name('show');
});
