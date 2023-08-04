<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 04 Aug 2023 15:02:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Landlord\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Lorisleiva\Actions\Concerns\AsController;

class LandlordLogin
{
    use AsController;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        Session::put('reloadLayout', '1');

        /** @var \App\Models\Auth\User $user */
        $user   = auth()->user();

        $language = $user->language;
        if($language) {
            app()->setLocale($language);
        }

        return redirect()->intended('/dashboard');
    }

}
