<?php

use App\Actions\Auth\CustomerUser\UpdateCustomerUser;
use App\Actions\Auth\User\UI\ShowResetPasswordUser;

Route::get('reset/password', ShowResetPasswordUser::class)->name('reset.password');
Route::patch('reset/password', [UpdateCustomerUser::class, 'inLoggedUser'])->name('update.password');
