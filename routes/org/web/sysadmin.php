<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Auth\User\ExportUsers;
use App\Actions\Organisation\Guest\ExportGuest;
use App\Actions\Organisation\Guest\UI\CreateGuest;
use App\Actions\Organisation\Guest\UI\EditGuest;
use App\Actions\Organisation\Guest\UI\IndexGuest;
use App\Actions\Organisation\Guest\UI\RemoveGuest;
use App\Actions\Organisation\Guest\UI\ShowGuest;
use App\Actions\Organisation\Organisation\UI\EditOrganisation;
use App\Actions\Organisation\OrganisationUser\UI\EditOrganisationUser;
use App\Actions\Organisation\OrganisationUser\UI\IndexOrganisationUsers;
use App\Actions\Organisation\OrganisationUser\UI\ShowOrganisationUser;
use App\Actions\UI\Organisation\SysAdmin\ShowSysAdminDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/sysadmin/dashboard');});
Route::get('/dashboard', ShowSysAdminDashboard::class)->name('dashboard');
Route::get('/system-settings', EditOrganisation::class)->name('organisation.edit');

Route::prefix('users')->name('users.')->group(function () {
    Route::get('', IndexOrganisationUsers::class)->name('index');
    Route::get('/export', ExportUsers::class)->name('export');
    Route::get('/{organisationUser}', ShowOrganisationUser::class)->name('show');
    Route::get('/{organisationUser}/edit', EditOrganisationUser::class)->name('edit');

});

Route::prefix('guests')->name('guests.')->group(function () {
    Route::get('', IndexGuest::class)->name('index');
    Route::get('/export', ExportGuest::class)->name('export');
    Route::get('/create', CreateGuest::class)->name('create');
    Route::get('/{guest}', ShowGuest::class)->name('show');
    Route::get('/{guest}/edit', EditGuest::class)->name('edit');
    Route::get('/guests/{guest}/delete', RemoveGuest::class)->name('remove');
});
