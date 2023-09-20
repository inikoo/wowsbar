<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 11:43:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetCustomerID
{
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()) {
            Config::set('global.customer_id', $request->user()->customer_id);
        }

        return $next($request);
    }
}
