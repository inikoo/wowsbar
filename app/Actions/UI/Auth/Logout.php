<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 14:18:33 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Auth;

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
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::put('reloadLayout', '1');
        return redirect('/');
    }

}
