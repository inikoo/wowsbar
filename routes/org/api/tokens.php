<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Dec 2023 15:39:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\SysAdmin\OrganisationUser\StoreOrganisationUserApiTokenFromCredentials;
use App\Actions\SysAdmin\OrganisationUser\StoreOrganisationUserApiTokenFromQRCode;
use Illuminate\Support\Facades\Route;

Route::name('tokens.')->group(function () {
    Route::post('tokens/qr-code', StoreOrganisationUserApiTokenFromQRCode::class)->name('qr-code.store');
    Route::post('tokens/credentials', StoreOrganisationUserApiTokenFromCredentials::class)->name('credentials.store');
});
