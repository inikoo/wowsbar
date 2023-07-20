<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 21:55:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\UI\GetFirstLoadProps;
use App\Http\Resources\UI\LoggedUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';


    public function share(Request $request): array
    {
        $user = $request->user();

        $firstLoadOnlyProps = [];

        if (!$request->inertia() or Session::get('reloadLayout')) {
            $firstLoadOnlyProps          = GetFirstLoadProps::run($user);
            $firstLoadOnlyProps['ziggy'] = function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            };

            if (Session::get('reloadLayout') == 'remove') {
                Session::forget('reloadLayout');
            }
            if (Session::get('reloadLayout')) {
                Session::put('reloadLayout', 'remove');
            }
        }

        return array_merge(
            $firstLoadOnlyProps,
            [
                'auth'  => [
                    'user' => $request->user() ? LoggedUserResource::make($request->user())->getArray() : null,
                ],
                'ziggy' => [
                    'location' => $request->url(),
                ],

            ],
            parent::share($request),
        );
    }
}
