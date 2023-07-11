<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 15 Jun 2023 11:40:28 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenancy\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Lorisleiva\Actions\Concerns\AsController;

class Login
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

        /** @var User $user */
        $user   = auth()->user();

        $language = $user->language;
        if($language) {
            app()->setLocale($language);
        }

        return redirect()->intended('/dashboard');
    }

}
