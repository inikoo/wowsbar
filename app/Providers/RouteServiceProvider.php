<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:34:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->configureRateLimiting();


        $this->routes(function () {


            Route::domain('delivery.'.config('app.domain'))
                ->name('delivery.')
                ->group(base_path('routes/delivery/app.php'));

            Route::middleware('org-web')
                ->domain(config('app.domain'))
                ->name('org.')
                ->group(base_path('routes/org/web/app.php'));




            Route::middleware('public')
                ->name('public.')
                ->group(base_path('routes/public/web/app.php'));

            Route::middleware('customer')
                ->prefix('auth')
                ->name('customer.')
                ->group(base_path('routes/customer/app.php'));


            /*

                        Route::middleware('web')
                            ->prefix('api')
                            ->name('customer.')
                            ->group(base_path('routes/customer/web/app.php'));


                        Route::middleware('public-web')
                            ->domain(config('app.domain'))
                            ->name('public.')
                            ->group(base_path('routes/public/web/app.php'));


                        /*

                        Route::middleware('customer-api')
                            ->prefix('api')
                            ->group(base_path('routes/customer/api/api.php'));
            */




        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });
    }
}
