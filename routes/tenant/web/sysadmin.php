<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:40:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Auth\User\UI\CreateUser;
use App\Actions\Auth\User\UI\EditUser;
use App\Actions\Auth\User\UI\IndexUsers;
use App\Actions\Auth\User\UI\ShowUser;
use App\Actions\UI\Authenticated\SysAdmin\EditSystemSettings;
use App\Actions\UI\Authenticated\SysAdmin\SysAdminDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', SysAdminDashboard::class)->name('dashboard');
Route::get('/system-settings', EditSystemSettings::class)->name('organisation.edit');

Route::get('/users', IndexUsers::class)->name('users.index');

Route::get('/users/create', CreateUser::class)->name('users.create');
Route::get('/users/{user:username}', ShowUser::class)->name('users.show');
Route::get('/users/{user:username}/edit', EditUser::class)->name('users.edit');
