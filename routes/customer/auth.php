<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:10:17 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Auth\User\Login;
use App\Actions\Auth\User\Logout;
use App\Actions\Auth\User\UI\ShowLogin;
use App\Actions\CRM\Customer\Register;
use App\Actions\UI\Public\Auth\ShowRegister;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:customer')->group(function () {
    Route::get('login', ShowLogin::class)->name('login');
    Route::post('login', Login::class)->name('login.store');

    Route::get('register', ShowRegister::class)
        ->name('register');

    Route::post('register', Register::class);

});

Route::middleware('auth')->group(function () {
    Route::post('logout', Logout::class)->name('logout');
});
