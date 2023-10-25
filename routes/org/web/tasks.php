<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:11:13 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Subscriptions\CustomerWebsite\UI\IndexSocialCustomerWebsites;
use App\Actions\Task\Task\UI\IndexTask;
use App\Actions\Task\TaskActivity\UI\IndexTaskActivity;
use App\Actions\Task\TaskActivity\UI\ShowTaskActivity;
use App\Actions\UI\Organisation\Catalogue\ShowSocialDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexTask::class)->name('index');

Route::prefix('activities')->as('activities.')->group(function () {
    Route::get('/', IndexTaskActivity::class)->name('index');
    Route::get('/{taskActivity}', ShowTaskActivity::class)->name('show');
});

Route::prefix('types')->as('types.')->group(function () {
//    Route::get('/', IndexTaskActivity::class)->name('index');
});
