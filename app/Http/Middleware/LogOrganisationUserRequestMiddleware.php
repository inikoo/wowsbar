<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 11 Oct 2023 16:07:48 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\SysAdmin\OrganisationUser\LogOrganisationUserRequest;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\OrganisationUser;
use Closure;
use Illuminate\Http\Request;

class LogOrganisationUserRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if(!str_starts_with($request->route()->getName(), 'org.')) {
            return $next($request);
        }

        if($request->route()->getName()=='org.logout') {
            return $next($request);
        }


        /* @var OrganisationUser $organisationUser */
        $organisationUser = $request->user();

        if (!app()->runningUnitTests() && $organisationUser && config('app.request_log.organisation')) {
            LogOrganisationUserRequest::dispatch(
                now(),
                [
                    'name'      => $request->route()->getName(),
                    'arguments' => $request->route()->originalParameters(),
                    'url'       => $request->path()
                ],
                $request->ip(),
                $request->header('User-Agent'),
                ElasticsearchUserRequestTypeEnum::VISIT->value,
                $organisationUser,
            );

        }

        return $next($request);
    }
}
