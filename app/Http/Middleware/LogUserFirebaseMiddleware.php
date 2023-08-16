<?php

namespace App\Http\Middleware;

use App\Actions\Firebase\DeleteUserLogFirebase;
use App\Actions\Firebase\StoreUserLogFirebase;
use Auth;
use Closure;
use Illuminate\Http\Request;

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
            $parent_type = 'Tenant';
            $parent_slug= app('currentTenant')->slug;
        }else{
            $parent_type='Organisation';
            $parent_slug=null;
        }
        $route = [
            'module'    => explode('.', request()->route()->getName())[0],
            'name'      => request()->route()->getName(),
            'arguments' => request()->route()->originalParameters()
        ];


            StoreUserLogFirebase::dispatch(
                $user,
                $parent_type,
                $parent_slug,
                $route);

            if($request->route()->getName() == 'logout') {
                DeleteUserLogFirebase::dispatch(
                    $user,
                    $parent_type,
                    $parent_slug,
                );
            }
        }

        return $next($request);
    }
}
