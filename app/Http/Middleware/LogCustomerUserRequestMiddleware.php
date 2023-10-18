<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 11 Oct 2023 17:06:28 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\Auth\CustomerUser\LogCustomerUserRequest;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\CustomerUser;
use Closure;
use Illuminate\Http\Request;

class LogCustomerUserRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if($request->route()->getName()=='customer.logout') {
            return $next($request);
        }

        /** @var CustomerUser $customerUser */
        $customerUser=$request->get('customerUser');


        if (!app()->runningUnitTests() && $customerUser && config('app.request_log.customers')) {

            LogCustomerUserRequest::run(
                now(),
                [
                    'name'      => $request->route()->getName(),
                    'arguments' => $request->route()->originalParameters(),
                    'url'       => $request->path()
                ],
                $request->ip(),
                $request->header('User-Agent'),
                ElasticsearchUserRequestTypeEnum::VISIT->value,
                $customerUser,
            );

        }

        return $next($request);
    }
}
