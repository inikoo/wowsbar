<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Customer\DownloadCustomersTemplate;
use App\Actions\HumanResources\Employee\DownloadEmployeeFailsExcel;
use App\Actions\HumanResources\Employee\DownloadEmployeesTemplate;
use App\Actions\Leads\Prospect\DownloadProspectsTemplate;
use App\Actions\SysAdmin\Guest\DownloadGuestsTemplate;

Route::prefix('templates')->as('templates.')->group(function () {
    Route::get('guests', DownloadGuestsTemplate::class)->name('guests');
    Route::get('employees', DownloadEmployeesTemplate::class)->name('employees');
    Route::get('customers', DownloadCustomersTemplate::class)->name('customers');
    Route::get('prospects', DownloadProspectsTemplate::class)->name('prospects');
});

Route::prefix('fails')->as('fails.')->group(function () {
    Route::get('employees', DownloadEmployeeFailsExcel::class)->name('employees.download');
});
