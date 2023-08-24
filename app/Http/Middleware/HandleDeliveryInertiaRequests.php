<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 09:33:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleDeliveryInertiaRequests extends Middleware
{
    protected $rootView = 'app-delivery';


    public function share(Request $request): array
    {
        $firstLoadOnlyProps = [];

        if (!$request->inertia()) {
            $firstLoadOnlyProps['ziggy'] = function () use ($request) {
                return array_merge(
                    (new Ziggy())->filter(['delivery.*'])->toArray(),
                    ['location' => $request->url(),]
                );
            };
        }

        return array_merge(
            $firstLoadOnlyProps,
            parent::share($request),
        );
    }
}
