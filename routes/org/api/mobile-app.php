<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Dec 2023 15:37:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Support\Facades\Route;

Route::name('mobile-app.')->group(function () {
    Route::middleware(["auth:sanctum"])->group(function () {
        Route::prefix("profile")
            ->name("profile.")
            ->group(__DIR__."/profile.php");
        Route::prefix("hr")
            ->name("hr.")
            ->group(__DIR__."/hr.php");
        Route::prefix("helpers")
            ->name("helpers.")
            ->group(__DIR__."/helpers.php");
    });
    require __DIR__."/tokens.php";
});
