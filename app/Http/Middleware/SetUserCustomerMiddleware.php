<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 11:43:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Models\Auth\CustomerUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetUserCustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            Config::set('global.customer_id', session('customer_id'));
            Config::set('global.customer_user_id', session('customer_user_id'));

            $request->merge(
                [
                    'customerUser' => CustomerUser::find(session('customer_user_id'))
                ]
            );
        }

        return $next($request);
    }
}
