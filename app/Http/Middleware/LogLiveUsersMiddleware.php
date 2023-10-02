<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 12:44:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\Firebase\StoreLiveUserToFirebase;
use App\Models\Auth\User;
use Closure;
use Illuminate\Http\Request;

class LogLiveUsersMiddleware
{
    use HasLiveUserLog;
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user && env('LIVE_USERS_LIST')) {


            list($loggedIn, $route)=$this->getRouteData($request);

            StoreLiveUserToFirebase::dispatch(
                user: $user,
                customerUlid: session('customer_ulid'),
                loggedIn: $loggedIn,
                route: $route
            );
        }

        return $next($request);
    }
}
