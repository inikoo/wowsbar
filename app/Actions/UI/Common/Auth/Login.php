<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:03:28 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Common\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class Login
{
    use AsController;

    private string $credentialHandler = 'username';
    private string $home = '/dashboard';
    private string $gate = 'web';
    private string $customTokenFirebasePrefix = 'web';


    public function __construct(ActionRequest $request)
    {
        if ($request->route()) {
            switch ($request->route()->getName()) {
                case 'org.login.store':
                    $this->gate = 'org';
                    $this->home = '/org/dashboard';
                    break;
                case 'public.login.store':
                    $this->credentialHandler = 'email';
                    $this->gate              = 'public';
                    break;
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

            throw ValidationException::withMessages([
                $this->credentialHandler => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));


        $request->session()->regenerate();
        Session::put('reloadLayout', '1');

        /** @var \App\Models\Auth\User $user */
        $user = auth($this->gate)->user();

        $language = $user->language;
        if ($language) {
            app()->setLocale($language);
        }


        $auth   = app('firebase.auth');
        $tenant = app('currentTenant');

        if ($this->gate == 'web') {
            $customTokenFirebaseKey = 'auth_tenants_firebase_token_'.$user->id;

            $customToken = $auth
                ->createCustomToken($tenant->slug, [
                    'scope'   => 'tenant',
                    'tenant_slug' => $tenant->slug
                ]);

            $auth->signInWithCustomToken($customToken);

            Cache::put($customTokenFirebaseKey, $customToken->toString(), 3600);
        }


        return back();
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
