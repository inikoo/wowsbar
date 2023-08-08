<?php

use App\Actions\UI\Landlord\Auth\LandlordConfirmablePassword;
use App\Actions\UI\Landlord\Auth\LandlordEmailVerification;
use App\Actions\UI\Landlord\Auth\LandlordLogin;
use App\Actions\UI\Landlord\Auth\LandlordLogout;
use App\Actions\UI\Landlord\Auth\LandlordNewPassword;
use App\Actions\UI\Landlord\Auth\LandlordPasswordResetLink;
use App\Actions\UI\Landlord\Auth\LandlordRegister;
use App\Actions\UI\Landlord\Auth\LandlordUpdatePassword;
use App\Actions\UI\Landlord\Auth\LandlordVerifyEmail;
use App\Actions\UI\Landlord\Auth\ShowLandlordConfirmablePassword;
use App\Actions\UI\Landlord\Auth\ShowLandlordEmailVerificationPrompt;
use App\Actions\UI\Landlord\Auth\ShowLandlordLogin;
use App\Actions\UI\Landlord\Auth\ShowLandlordNewPassword;
use App\Actions\UI\Landlord\Auth\ShowLandlordPasswordResetLink;
use App\Actions\UI\Landlord\Auth\ShowLandlordRegister;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:landlord')->group(function () {
    Route::get('register', ShowLandlordRegister::class)
                ->name('register');

    Route::post('register', LandlordRegister::class);

    Route::get('login', ShowLandlordLogin::class)->name('login');

    Route::post('login', LandlordLogin::class)->name('login.store');

    Route::get('forgot-password', ShowLandlordPasswordResetLink::class)
                ->name('password.request');

    Route::post('forgot-password', LandlordPasswordResetLink::class)
                ->name('password.email');

    Route::get('reset-password/{token}', ShowLandlordNewPassword::class)
                ->name('password.reset');

    Route::post('reset-password', LandlordNewPassword::class)
                ->name('password.store');
});

Route::middleware('auth:landlord')->group(function () {
    Route::get('verify-email', ShowLandlordEmailVerificationPrompt::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', LandlordVerifyEmail::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', LandlordEmailVerification::class)
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', ShowLandlordConfirmablePassword::class)
                ->name('password.confirm');

    Route::post('confirm-password', LandlordConfirmablePassword::class);

    Route::put('password', LandlordUpdatePassword::class)->name('password.update');

    Route::post('logout', LandlordLogout::class)
                ->name('logout');
});
