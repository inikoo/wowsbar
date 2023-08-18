<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Auth\OrganisationUser\UI\IndexOrganisationUsers;
use App\Actions\Tenant\Auth\User\ExportUsers;
use App\Actions\Tenant\Auth\User\UI\CreateUser;
use App\Actions\Tenant\Auth\User\UI\EditUser;
use App\Actions\Tenant\Auth\User\UI\ShowUser;
use App\Actions\UI\Organisation\SysAdmin\SysAdminDashboard;
use App\Actions\UI\Tenant\SysAdmin\EditSystemSettings;
use Illuminate\Support\Facades\Route;

Route::get('/', SysAdminDashboard::class)->name('dashboard');
Route::get('/settings', EditSystemSettings::class)->name('settings.edit');

Route::get('/users', IndexOrganisationUsers::class)->name('users.index');
Route::get('/users/export', ExportUsers::class)->name('users.export');

Route::get('/users/create', CreateUser::class)->name('users.create');
Route::get('/users/{user:username}', ShowUser::class)->name('users.show');
Route::get('/users/{user:username}/edit', EditUser::class)->name('users.edit');
