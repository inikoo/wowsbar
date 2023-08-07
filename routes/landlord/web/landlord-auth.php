<?php

use App\Actions\UI\Landlord\Auth\LandlordConfirmablePassword;
use App\Actions\UI\Landlord\Auth\LandlordEmailVerification;
use App\Actions\UI\Landlord\Auth\LandlordLogin;
use App\Actions\UI\Landlord\Auth\LandlordNewPassword;
use App\Actions\UI\Landlord\Auth\LandlordPasswordResetLink;
use App\Actions\UI\Landlord\Auth\LandlordRegister;
use App\Actions\UI\Landlord\Auth\LandlordVerifyEmail;
use App\Actions\UI\Landlord\Auth\ShowLandlordConfirmablePassword;
use App\Actions\UI\Landlord\Auth\ShowLandlordEmailVerificationPrompt;
use App\Actions\UI\Landlord\Auth\ShowLandlordLogin;
use App\Actions\UI\Landlord\Auth\ShowLandlordNewPassword;
use App\Actions\UI\Landlord\Auth\ShowLandlordPasswordResetLink;
use App\Actions\UI\Landlord\Auth\ShowLandlordRegister;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
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

Route::middleware('auth')->group(function () {
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

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
