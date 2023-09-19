<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:10:17 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Common\Auth\Login;
use App\Actions\UI\Common\Auth\Logout;
use App\Actions\UI\Common\Auth\ShowLogin;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:customer')->group(function () {
    Route::get('login', ShowLogin::class)->name('login');
    Route::post('login', Login::class)->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', Logout::class)->name('logout');
});
