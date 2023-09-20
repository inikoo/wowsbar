<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Auth\User\UI\CreateUser;
use App\Actions\Auth\User\UI\EditUser;
use App\Actions\Auth\User\UI\IndexUsers;
use App\Actions\Auth\User\UI\ShowUser;
use App\Actions\UI\Customer\SysAdmin\EditSystemSettings;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowSysAdminDashboard::class)->name('dashboard');
Route::get('/system-settings', EditSystemSettings::class)->name('organisation.edit');

Route::get('/users', IndexUsers::class)->name('users.index');

Route::get('/users/create', CreateUser::class)->name('users.create');
Route::get('/users/{user:username}', ShowUser::class)->name('users.show');
Route::get('/users/{user:username}/edit', EditUser::class)->name('users.edit');
