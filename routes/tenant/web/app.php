<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 14:18:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware([
    "web",
])->group(function () {


    Route::middleware(["auth"])->group(function () {
        Route::get('/', function () {
            return redirect('/dashboard');
        });
        Route::prefix("dashboard")
            ->name("dashboard.")
            ->group(__DIR__."/dashboard.php");
        Route::prefix("tenant")
            ->name("tenant.")
            ->group(__DIR__."/tenant.php");
        Route::prefix("portfolio")
            ->name("portfolio.")
            ->group(__DIR__."/portfolio.php");
        Route::prefix("media")
            ->name("media.")
            ->group(__DIR__."/media.php");
        Route::prefix("profile")
            ->name("profile.")
            ->group(__DIR__."/profile.php");
        Route::prefix("sysadmin")
            ->name("sysadmin.")
            ->group(__DIR__."/sysadmin.php");
        Route::prefix("models")
            ->name("models.")
            ->group(__DIR__."/models.php");

    });


    require __DIR__."/auth.php";
});
