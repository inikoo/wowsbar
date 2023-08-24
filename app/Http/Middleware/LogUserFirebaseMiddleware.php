<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 12:44:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\Firebase\StoreUserLogFirebase;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LogUserFirebaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user   = $request->user();

        if($user && env('LIVE_USERS_LIST')) {

            if(Auth::getDefaultDriver() == 'web') {
                $parentTpe = 'Tenant';
                $parentSlug = app('currentTenant')->slug;
            } else {
                $parentTpe='Organisation';
                $parentSlug=null;
            }



            if($request->route()->getName() == 'logout') {
                $loggedIn=false;
                $route=null;
            }else{
                $loggedIn=true;
                $route = [
                    'icon'      => Arr::get($request->route()->action, 'icon'),
                    'label'     => Arr::get($request->route()->action, 'label'),
                    'name'      => request()->route()->getName(),
                    'arguments' => request()->route()->originalParameters()
                ];
            }

            StoreUserLogFirebase::dispatch(
                user:$user,
                parentType:$parentTpe,
                parentSlug:$parentSlug,
                logedIn:$loggedIn,
                route:$route
            );


        }

        return $next($request);
    }
}
