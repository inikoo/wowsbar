<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:03:28 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Common\Auth;

use App\Actions\Auth\User\Hydrators\UserHydrateFailLogin;
use App\Actions\Auth\User\Hydrators\UserHydrateLogin;
use App\Actions\Organisation\Auth\OrganisationUser\Hydrators\OrganisationUserHydrateFailLogin;
use App\Actions\Organisation\Auth\OrganisationUser\Hydrators\OrganisationUserHydrateLogin;
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

    private string $credentialHandler         = 'username';
    private string $home                      = '/dashboard';
    private string $gate                      = '';


    public function __construct(ActionRequest $request)
    {
        if ($request->route()) {

            $routeName =$request->route()->getName();
            $this->gate=match ($request->route()->getName()) {
                'org.login.store'=> 'org',
                default          => 'customer'
            };
            if($routeName=='customer.login.store') {
                $this->home='auth/dashboard';
            }

        }
    }


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
                $this->credentialHandler => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));


        switch ($this->gate) {
            case 'web':
            case 'public':
                Config::set('global.customer_id', Auth::guard($this->gate)->user()->customer_id);
                UserHydrateLogin::dispatch(Auth::guard($this->gate)->user(), request()->ip(), now());
                break;
            case 'org':
                OrganisationUserHydrateLogin::dispatch(Auth::guard($this->gate)->user(), request()->ip(), now());
        }


        $request->session()->regenerate();
        Session::put('reloadLayout', '1');

        /** @var \App\Models\Auth\User $user */
        $user = auth($this->gate)->user();

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
            $this->credentialHandler => ['required', $this->credentialHandler == 'email' ? 'email' : 'string'],
            'password'               => ['required', 'string'],
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
