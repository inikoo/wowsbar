<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\User\Hydrators\UserHydrateFailLogin;
use App\Actions\Auth\User\Hydrators\UserHydrateLogin;
use App\Actions\Organisation\OrganisationUser\Hydrators\OrganisationUserHydrateFailLogin;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class Login
{
    use AsController;

    private string $credentialHandler = 'email';
    private string $home              = 'auth/dashboard';
    private string $gate              = 'customer';


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(ActionRequest $request): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request);

        if (!Auth::guard($this->gate)->attempt(
            array_merge($request->validated(), ['status' => true]),
            $request->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey($request));
            $this->userFailLogin();
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        /** @var User $user */
        $user = Auth::guard($this->gate)->user();

        /** @var CustomerUser $customerUser */
        $customerUser=$user->customerUsers()->where('status',true)->first();
        if(!$customerUser) {
            RateLimiter::hit($this->throttleKey($request));
            $this->userFailLogin();

            Auth::guard($this->gate)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }


        Config::set('global.customer_id', $customerUser->customer->id);
        Config::set('global.customer_user_id', $customerUser->id);

        session([
            'customer_user_id'=>$customerUser->id,
            'customer_id'  => $customerUser->customer->id,
            'customer_slug'=> $customerUser->customer->slug
        ]);
        UserHydrateLogin::dispatch(Auth::guard($this->gate)->user(), request()->ip(), now());

        $request->session()->regenerate();
        Session::put('reloadLayout', '1');


        $language = $user->language;
        if ($language) {
            app()->setLocale($language);
        }

        return back();
    }


    public function userFailLogin(): void
    {
        switch ($this->gate) {
            case 'web':
            case 'public':
                UserHydrateFailLogin::dispatch(Auth::guard($this->gate)->user(), request()->ip(), now());
                break;
            case 'org':
                OrganisationUserHydrateFailLogin::dispatch(Auth::guard($this->gate)->user(), request()->ip(), now());
        }
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $this->handle($request);

        return redirect()->intended($this->home);
    }


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(ActionRequest $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            $this->credentialHandler => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(ActionRequest $request): string
    {
        return Str::transliterate(Str::lower($request->input($this->credentialHandler)).'|'.$request->ip());
    }
}
