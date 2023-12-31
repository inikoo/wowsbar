<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Support\Facades\Route;

Route::middleware(["org-web"])->group(function () {

    Route::middleware(["org-auth:org",])->group(function () {
        Route::get('/', function () {
            return redirect('/dashboard');
        });
        Route::middleware(["org-reset-pass"])->group(function () {
            Route::prefix("dashboard")
                ->name("dashboard.")
                ->group(__DIR__ . "/dashboard.php");
            Route::prefix("shops")
                ->name("shops.")
                ->group(__DIR__ . "/shops.php");
            Route::prefix("crm")
                ->name("crm.")
                ->group(__DIR__ . "/crm.php");
            Route::prefix("websites")
                ->name("websites.")
                ->group(__DIR__ . "/websites.php");
            Route::prefix("subscriptions")
                ->name("subscriptions.")
                ->group(__DIR__."/subscriptions.php");
            Route::prefix("catalogue")
                ->name("catalogue.")
                ->group(__DIR__ . "/catalogue.php");
            Route::prefix("social")
                ->name("social.")
                ->group(__DIR__ . "/social.php");
            Route::prefix("caas")
                ->name("caas.")
                ->group(__DIR__ . "/caas.php");
            Route::prefix("seo")
                ->name("seo.")
                ->group(__DIR__ . "/seo.php");
            Route::prefix("ppc")
                ->name("ppc.")
                ->group(__DIR__ . "/ppc.php");
            Route::prefix("media")
                ->name("media.")
                ->group(__DIR__ . "/media.php");
            Route::prefix("profile")
                ->name("profile.")
                ->group(__DIR__ . "/profile.php");
            Route::prefix("sysadmin")
                ->name("sysadmin.")
                ->group(__DIR__ . "/sysadmin.php");
            Route::prefix("models")
                ->name("models.")
                ->group(__DIR__ . "/models.php");
            Route::prefix("search")
                ->name("search.")
                ->group(__DIR__ . "/search.php");
            Route::prefix("helpers")
                ->name("helpers.")
                ->group(__DIR__ . "/helpers.php");
            Route::prefix("hr")
                ->name("hr.")
                ->group(__DIR__ . "/hr.php");
            Route::prefix("accounting")
                ->name("accounting.")
                ->group(__DIR__ . "/accounting.php");
            Route::prefix("downloads")
                ->name("downloads.")
                ->group(__DIR__ . "/downloads.php");
            Route::get('phpinfo', function () {
                phpinfo();
            })->name('phpinfo');
            Route::prefix("labour")
                ->name("labour.")
                ->group(__DIR__ . "/labour.php");
            Route::prefix("queries")
                ->name("query.")
                ->group(__DIR__ . "/query.php");
            Route::prefix("json")
                ->name("json.")
                ->group(__DIR__ . "/json.php");
            Route::prefix("uploads")
                ->name("uploads.")
                ->group(__DIR__ . "/uploads.php");
        });
    });


    Route::prefix("unsubscribe")
        ->name("unsubscribe.")
        ->group(__DIR__ . "/unsubscribe.php");

    require __DIR__ . "/auth.php";
});
