<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\OrganisationUser\Login;
use App\Actions\Organisation\OrganisationUser\UI\ShowResetOrganisationUserPassword;
use App\Actions\Organisation\OrganisationUser\UpdateOrganisationUserPassword;
use App\Actions\UI\Common\Auth\Logout;
use App\Actions\UI\Common\Auth\ShowLogin;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:org')->group(function () {
    Route::get('login', ShowLogin::class)->name('login');
    Route::post('login', Login::class)->name('login.store');
});

Route::middleware('org-auth:org')->group(function () {
    Route::post('logout', Logout::class)->name('logout');
    Route::get('reset/password', ShowResetOrganisationUserPassword::class)->name('reset-password.edit');
    Route::patch('reset/password', UpdateOrganisationUserPassword::class)->name('reset-password.update');
});
