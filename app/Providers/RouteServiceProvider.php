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

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {

        $this->configureRateLimiting();



        Route::middleware('landlord-web')
            ->domain(config('app.domain'))
            ->namespace($this->namespace)
            ->name('landlord.')
            ->group(base_path('routes/landlord/web/landlord-app.php'));


        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/tenant/api/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/tenant/web/app.php'));
        });

    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });
    }
}
