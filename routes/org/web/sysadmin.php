<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Auth\CustomerUser\UI\CreateCustomerUser;
use App\Actions\Auth\User\ExportUsers;
use App\Actions\Auth\User\UI\EditUser;
use App\Actions\Organisation\Guest\DownloadGuestsTemplate;
use App\Actions\Organisation\Organisation\UI\EditOrganisation;
use App\Actions\Organisation\OrganisationUser\UI\IndexOrganisationUsers;
use App\Actions\Organisation\OrganisationUser\UI\ShowOrganisationUser;
use App\Actions\UI\Organisation\SysAdmin\ShowSysAdminDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowSysAdminDashboard::class)->name('dashboard');
Route::get('/system-settings', EditOrganisation::class)->name('organisation.edit');

Route::get('/users', IndexOrganisationUsers::class)->name('users.index');
Route::get('/users/export', ExportUsers::class)->name('users.export');

Route::get('/users/create', CreateCustomerUser::class)->name('users.create');
Route::get('/users/{organisationUser}', ShowOrganisationUser::class)->name('users.show');
Route::get('/users/{user:username}/edit', EditUser::class)->name('users.edit');

Route::get('/guests/uploads/template/download', DownloadGuestsTemplate::class)->name('guest.uploads.template.download');
