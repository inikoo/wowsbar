<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 14:23:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

trait HasLiveUserLog
{
    protected function getRouteData(Request $request): array
    {

        if ($request->route()->getName() == 'logout') {
            $loggedIn = false;
            $route    = null;
        } else {
            $loggedIn = true;
            $route    = [
                'icon'      => Arr::get($request->route()->action, 'icon'),
                'label'     => Arr::get($request->route()->action, 'label'),
                'name'      => request()->route()->getName(),
                'arguments' => request()->route()->originalParameters()
            ];
        }

        return [$loggedIn,$route];

    }
}
