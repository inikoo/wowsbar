<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:10:11 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use Illuminate\Support\Facades\Route;

Route::middleware([
    "customer",
])->group(function () {


    Route::middleware(["auth:customer"])->group(function () {
        Route::get('/', function () {
            return redirect('/app/dashboard');
        });
        Route::middleware(["reset-pass"])->group(function () {
        Route::prefix("dashboard")
            ->name("dashboard.")
            ->group(__DIR__."/dashboard.php");
        Route::prefix("portfolio")
            ->name("portfolio.")
            ->group(__DIR__."/portfolio.php");
        Route::prefix("prospects")
            ->name("prospects.")
            ->group(__DIR__."/prospects.php");
        Route::prefix("seo")
            ->name("seo.")
            ->group(__DIR__."/seo.php");
        Route::prefix("ppc")
            ->name("ppc.")
            ->group(__DIR__."/ppc.php");
        Route::prefix("banners")
            ->name("banners.")
            ->group(__DIR__."/banners.php");
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
        Route::prefix("search")
            ->name("search.")
            ->group(__DIR__."/search.php");
        Route::prefix("export")
            ->name("export.")
            ->group(__DIR__."/export.php");
        Route::prefix("billings")
                ->name("billings.")
                ->group(__DIR__."/billing.php");
        });

        Route::prefix('passwords')
            ->name('passwords.')
            ->group(__DIR__ . "/passwords.php");

    });

    require __DIR__."/auth.php";
});
