<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 15 Jun 2023 11:40:28 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Landlord\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsController;

class LandlordLogin
{
    use AsController;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(LoginRequest $request): RedirectResponse
    {
        $this->authenticate($request);

        $request->session()->regenerate();
        Session::put('reloadLayout', '1');

        /** @var \App\Models\Auth\User $user */
        $user   = auth('landlord')->user();

        $language = $user->language;
        if($language) {
            app()->setLocale($language);
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(LoginRequest $request): void
    {
        $request->ensureIsNotRateLimited();

        if (!Auth::guard('landlord')->attempt(
            array_merge($request->validated(), ['status' => true]),
            $request->boolean('remember')
        )) {
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($request->throttleKey());
    }

}
