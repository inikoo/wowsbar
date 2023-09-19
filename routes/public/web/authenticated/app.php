<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */



Route::name('authenticated.')->middleware('public-auth:public')->group(function () {



        Route::get('/', function () {
            return redirect('/dashboard');
        });

        Route::prefix("dashboard")
            ->name("dashboard.")
            ->group(__DIR__."/dashboard.php");

       /*
        Route::prefix("customer")
            ->name("customer.")
            ->group(__DIR__."/customer.php");
       */
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
        Route::prefix("search")
            ->name("search.")
            ->group(__DIR__."/search.php");
        Route::prefix("export")
            ->name("export.")
            ->group(__DIR__."/export.php");

    });



