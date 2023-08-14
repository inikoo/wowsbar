<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:22:46 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Common\Auth\Login;
use App\Actions\UI\Common\Auth\Logout;
use App\Actions\UI\Public\Auth\ConfirmablePassword;
use App\Actions\UI\Public\Auth\EmailVerification;
use App\Actions\UI\Public\Auth\NewPassword;
use App\Actions\UI\Public\Auth\PasswordResetLink;
use App\Actions\UI\Public\Auth\Register;
use App\Actions\UI\Public\Auth\UpdatePassword;
use App\Actions\UI\Public\Auth\VerifyEmail;
use App\Actions\UI\Public\Auth\ShowConfirmablePassword;
use App\Actions\UI\Public\Auth\ShowEmailVerificationPrompt;
use App\Actions\UI\Public\Auth\ShowNewPassword;
use App\Actions\UI\Public\Auth\ShowPasswordResetLink;
use App\Actions\UI\Public\Auth\ShowRegister;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:public')->group(function () {
    Route::get('register', ShowRegister::class)->name('register');
    Route::post('register', Register::class);
    Route::get('login', \App\Actions\UI\Common\Auth\ShowLogin::class)->name('login');
    Route::post('login', Login::class)->name('login.store');
    Route::get('forgot-password', ShowPasswordResetLink::class)->name('password.request');

    Route::post('forgot-password', PasswordResetLink::class)->name('password.email');

    Route::get('reset-password/{token}', ShowNewPassword::class)
                ->name('password.reset');

    Route::post('reset-password', NewPassword::class)
                ->name('password.store');
});

Route::middleware('public-auth:public')->group(function () {
    Route::get('verify-email', ShowEmailVerificationPrompt::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmail::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', EmailVerification::class)
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', ShowConfirmablePassword::class)
                ->name('password.confirm');

    Route::post('confirm-password', ConfirmablePassword::class);

    Route::put('password', UpdatePassword::class)->name('password.update');

    Route::post('logout', Logout::class)->name('logout');
});
