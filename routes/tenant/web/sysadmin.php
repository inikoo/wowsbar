<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:40:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Tenancy\User\UI\CreateUser;
use App\Actions\Tenancy\User\UI\EditUser;
use App\Actions\Tenancy\User\UI\IndexUsers;
use App\Actions\Tenancy\User\UI\ShowUser;
use App\Actions\Tenancy\User\ExportUsers;
use App\Actions\UI\SysAdmin\SysAdminDashboard;
use App\Actions\UI\SysAdmin\EditSystemSettings;
use Illuminate\Support\Facades\Route;

Route::get('/', SysAdminDashboard::class)->name('dashboard');
Route::get('/settings', EditSystemSettings::class)->name('settings.edit');

Route::get('/users', IndexUsers::class)->name('users.index');
Route::get('/users/export', ExportUsers::class)->name('users.export');

Route::get('/users/create', CreateUser::class)->name('users.create');
Route::get('/users/{user}', ShowUser::class)->name('users.show');
Route::get('/users/{user}/edit', EditUser::class)->name('users.edit');



