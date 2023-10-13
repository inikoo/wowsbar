<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 12 Oct 2023 16:28:21 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuditUserResolver implements \OwenIt\Auditing\Contracts\UserResolver
{
    public static function resolve()
    {
        $guards = Config::get('audit.user.guards', [
            config('auth.defaults.guard')
        ]);


        foreach ($guards as $guard) {
            try {
                $authenticated = Auth::guard($guard)->check();
            } catch (\Exception $exception) {
                continue;
            }

            if (true === $authenticated) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}
