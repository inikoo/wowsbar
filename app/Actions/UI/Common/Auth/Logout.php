<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 17:29:20 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Common\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Lorisleiva\Actions\Concerns\AsController;

class Logout
{
    use AsController;


    public function handle(Request $request): RedirectResponse
    {
        switch ($request->route()->getName()) {
            case 'org.logout':
                $gate        = 'org';
                $redirectUrl = '/org/login';
                break;
            case 'public.logout':
                $redirectUrl = '/';
                $gate        = 'public';
                break;
            default:
                $gate        = 'web';
                $redirectUrl = '/login';
        }

        Auth::guard($gate)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::put('reloadLayout', '1');

        return redirect($redirectUrl);
    }

}
