<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 21:43:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Facades\Actions;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Validator::extend('iunique', function ($attribute, $value, $parameters, $validator) {
            if (isset($parameters[1])) {
                [$connection]  = $validator->parseTable($parameters[0]);
                $wrapped       = DB::connection($connection)->getQueryGrammar()->wrap($parameters[1]);
                $parameters[1] = DB::raw("lower($wrapped)");
            }

            return $validator->validateUnique($attribute, Str::lower($value), $parameters);
        }, trans('validation.iunique'));

        if ($this->app->runningInConsole()) {
            Actions::registerCommands();
        }


        Relation::morphMap(
            [
                'Organisation'     => 'App\Models\Organisation\Organisation',
                'User'             => 'App\Models\Auth\User',
                'PortfolioWebsite' => 'App\Models\Portfolio\PortfolioWebsite',
                'Banner'           => 'App\Models\Portfolio\Banner',
                'OrganisationUser' => 'App\Models\Auth\OrganisationUser',
                'Guest'            => 'App\Models\Auth\Guest',
                'Shop'             => 'App\Models\Market\Shop',
                'Website'          => 'App\Models\Web\Website',
                'Webpage'          => 'App\Models\Web\Webpage',
                'Customer'         => 'App\Models\CRM\Customer',
                'Prospect'         => 'App\Models\Leads\Prospect',
                'Workplace'        => 'App\Models\HumanResources\Workplace',
                'Product'          => 'App\Models\Catalogue\Product',
                'ProductCategory'  => 'App\Models\Catalogue\ProductCategory',
                'CustomerUser'     => 'App\Models\Auth\CustomerUser',
                'Employee'         => 'App\Models\HumanResources\Employee',
                'CustomerWebsite'  => 'App\Models\Portfolios\CustomerWebsite'

            ]
        );
    }
}
