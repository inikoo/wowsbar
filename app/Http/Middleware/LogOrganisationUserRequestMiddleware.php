<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 11 Oct 2023 16:07:48 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\Organisation\OrganisationUser\LogOrganisationUserRequest;
use App\Enums\Elasticsearch\ElasticsearchTypeEnum;
use App\Models\Auth\OrganisationUser;
use Closure;
use Illuminate\Http\Request;

class LogOrganisationUserRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
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
                ElasticsearchTypeEnum::VISIT->value,
                $organisationUser,
            );

        }

        return $next($request);
    }
}
