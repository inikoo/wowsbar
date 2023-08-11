<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:22:46 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Public\Auth\PublicConfirmablePassword;
use App\Actions\UI\Public\Auth\PublicEmailVerification;
use App\Actions\UI\Public\Auth\PublicLogin;
use App\Actions\UI\Public\Auth\PublicLogout;
use App\Actions\UI\Public\Auth\PublicNewPassword;
use App\Actions\UI\Public\Auth\PublicPasswordResetLink;
use App\Actions\UI\Public\Auth\PublicRegister;
use App\Actions\UI\Public\Auth\PublicUpdatePassword;
use App\Actions\UI\Public\Auth\PublicVerifyEmail;
use App\Actions\UI\Public\Auth\ShowPublicConfirmablePassword;
use App\Actions\UI\Public\Auth\ShowPublicEmailVerificationPrompt;
use App\Actions\UI\Public\Auth\ShowPublicLogin;
use App\Actions\UI\Public\Auth\ShowPublicNewPassword;
use App\Actions\UI\Public\Auth\ShowPublicPasswordResetLink;
use App\Actions\UI\Public\Auth\ShowPublicRegister;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:public')->group(function () {
    Route::get('register', ShowPublicRegister::class)
                ->name('register');

    Route::post('register', PublicRegister::class);

    Route::get('login', ShowPublicLogin::class)->name('login');

    Route::post('login', PublicLogin::class)->name('login.store');

    Route::get('forgot-password', ShowPublicPasswordResetLink::class)
                ->name('password.request');

    Route::post('forgot-password', PublicPasswordResetLink::class)
                ->name('password.email');

    Route::get('reset-password/{token}', ShowPublicNewPassword::class)
                ->name('password.reset');

    Route::post('reset-password', PublicNewPassword::class)
                ->name('password.store');
});

Route::middleware('auth:public')->group(function () {
    Route::get('verify-email', ShowPublicEmailVerificationPrompt::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', PublicVerifyEmail::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', PublicEmailVerification::class)
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', ShowPublicConfirmablePassword::class)
                ->name('password.confirm');

    Route::post('confirm-password', PublicConfirmablePassword::class);

    Route::put('password', PublicUpdatePassword::class)->name('password.update');

    Route::post('logout', PublicLogout::class)
                ->name('logout');
});
