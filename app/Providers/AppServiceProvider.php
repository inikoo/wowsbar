<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 21:43:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
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
        if ($this->app->runningInConsole()) {
            Actions::registerCommands();
        }
        Relation::morphMap(
            [
                'Organisation'         => 'App\Models\Organisation\Organisation',
                'Tenant'           => 'App\Models\Tenancy\Tenant',
                'User'             => 'App\Models\Auth\User',
                'Website'          => 'App\Models\Portfolio\Website',
                'ContentBlock'     => 'App\Models\Portfolio\ContentBlock',
                'OrganisationUser' => 'App\Models\Organisation\OrganisationUser',
                'PublicUser'       => 'App\Models\Auth\OrganisationUser',

            ]
        );
    }
}
