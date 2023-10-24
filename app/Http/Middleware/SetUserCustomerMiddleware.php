<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 11:43:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetUserCustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {



            if(session('customer_id') and  session('customer_user_id')) {
                Config::set('global.customer_id', session('customer_id'));
                Config::set('global.customer_user_id', session('customer_user_id'));
            } else {
                if($request->cookie('customerUser')) {
                    $customerUser=CustomerUser::find($request->cookie('customerUser'));
                } else {
                    $customerUser = $request->user()->customerUsers()->where('status', true)->first();
                    Cookie::queue('customerUser', $customerUser->id, 60 * 24 * 365);

                }

                Config::set('global.customer_id', $customerUser->customer->id);
                Config::set('global.customer_user_id', $customerUser->id);


                session([
                    'customer_user_id' => $customerUser->id,
                    'customer_id'      => $customerUser->customer->id,
                    'customer_slug'    => $customerUser->customer->slug,
                    'customer_name'    => $customerUser->customer->name,
                    'customer_ulid'    => $customerUser->customer->ulid
                ]);

            }



            $request->merge(
                [
                    'customer'     => Customer::find(session('customer_id')),
                    'customerUser' => CustomerUser::find(session('customer_user_id'))

                ]
            );
        }

        return $next($request);
    }
}
