<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 31 Jul 2023 12:44:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Http\Resources\UI\LoggedUserResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandlePublicInertiaRequests extends Middleware
{
    protected $rootView = 'app-public';


    public function share(Request $request): array
    {
        $firstLoadOnlyProps['ziggy'] = function () use ($request) {
            return array_merge((new Ziggy())->toArray(), [
                'location' => $request->url(),
            ]);
        };


        return array_merge(
            $firstLoadOnlyProps,
            [
                'structure' => $request->get('website')->compiled_layout,
                'auth'      => [
                    'user' => $request->user() ? LoggedUserResource::make($request->user())->getArray() : null,
                ],
            ],
            parent::share($request),
        );

    }
}
