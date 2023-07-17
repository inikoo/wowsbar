<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 21:55:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Middleware;

use App\Actions\UI\GetFirstLoadProps;
use App\Http\Resources\UI\LoggedUserResource;
use App\Http\Resources\UniversalSearch\UniversalSearchResource;
use App\Models\Search\UniversalSearch;
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
            $firstLoadOnlyProps =GetFirstLoadProps::run($user);

            if (Session::get('reloadLayout') == 'remove') {
                Session::forget('reloadLayout');
            }
            if (Session::get('reloadLayout')) {
                Session::put('reloadLayout', 'remove');
            }
        }


        return array_merge(
            parent::share($request),
            $firstLoadOnlyProps,
            [
            'auth' => [
                'user' => $request->user() ? LoggedUserResource::make($request->user())->getArray() : null,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'searchQuery'       => fn () => $request->session()->get('fastSearchQuery'),
            'searchResults'     => function () use ($request) {
                $query=$request->session()->get('fastSearchQuery');
                if ($query) {
                    $items = UniversalSearch::search($query)->paginate(5);
                    return UniversalSearchResource::collection($items);
                } else {
                    return ['data' => []];
                }
            },
        ]
        );
    }
}
