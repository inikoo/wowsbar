<?php

namespace App\Http\Middleware;

use App\Actions\Firebase\DeleteUserLogFirebase;
use App\Actions\Firebase\StoreUserLogFirebase;
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

        if(!$parent = app('currentTenant')) {
            $parent = organisation();
        }

        $route = [
            'module'    => explode('.', request()->route()->getName())[0],
            'name'      => request()->route()->getName(),
            'arguments' => request()->route()->originalParameters()
        ];

        if($user && env('LIVE_USERS_LIST')) {
            StoreUserLogFirebase::dispatch($user, $parent, $route);

            if($request->route()->getName() == 'logout') {
                DeleteUserLogFirebase::dispatch($user, $parent);
            }
        }

        return $next($request);
    }
}
