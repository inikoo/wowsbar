<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 14:12:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\Firebase\StoreLiveOrganisationUserToFirebase;
use App\Models\Auth\OrganisationUser;
use Closure;
use Illuminate\Http\Request;

class LogLiveOrganisationUsersMiddleware
{
    use HasLiveUserLog;

    public function handle(Request $request, Closure $next)
    {
        /** @var OrganisationUser $organisationUser */
        $organisationUser = $request->user();

        if ($organisationUser && config('app.live_list.organisation')) {
            list($loggedIn, $route)=$this->getRouteData($request);
            StoreLiveOrganisationUserToFirebase::dispatch(
                organisationUser: $organisationUser,
                loggedIn: $loggedIn,
                route: $route
            );
        }

        return $next($request);
    }
}
