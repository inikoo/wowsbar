<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 20:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Auth\Login;
use App\Actions\UI\Auth\Logout;
use App\Actions\UI\Auth\ShowLogin;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', ShowLogin::class)->name('login');
    Route::post('login', Login::class)->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', Logout::class)->name('logout');
});
