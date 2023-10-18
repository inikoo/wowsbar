<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

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
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Login
{
    use AsAction;

    private string $credentialHandler = 'email';


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(ActionRequest $request): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request);

        if (!Auth::guard('customer')->attempt(
            array_merge($request->validated(), ['status' => true]),
            $request->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey($request));
            LogUserFailLogin::dispatch(
                $request->get('website'),
                $request->validated(),
                request()->ip(),
                $request->header('User-Agent'),
                now()
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        /** @var User $user */
        $user = Auth::guard('customer')->user();

        $this->logCustomerUser($user, $request);

        return back();
    }


    public function logCustomerUser(User $user, ActionRequest $request): void
    {
        /** @var CustomerUser $customerUser */
        $customerUser = $user->customerUsers()->where('status', true)->first();
        if (!$customerUser) {
            Auth::guard('customer')->logout();
            session()->invalidate();
            session()->regenerateToken();

            abort(419, 'CustomerUser not associated with user, cant log in');
        }


        Config::set('global.customer_id', $customerUser->customer->id);
        Config::set('global.customer_user_id', $customerUser->id);

        session([
            'customer_user_id' => $customerUser->id,
            'customer_id'      => $customerUser->customer->id,
            'customer_slug'    => $customerUser->customer->slug,
            'customer_name'    => $customerUser->customer->name,
            'customer_ulid'    => $customerUser->customer->ulid
        ]);
        LogUserLogin::dispatch(
            $request->get('website'),
            $customerUser,
            request()->ip(),
            $request->header('User-Agent'),
            now()
        );

        session()->regenerate();
        Session::put('reloadLayout', '1');


        $language = $user->language;
        if ($language) {
            app()->setLocale($language);
        }
    }


    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }


    public function asController(ActionRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $this->handle($request);
        Inertia::setRootView('app-customer');

        $url = session()->pull('url.intended', 'app/dashboard');

        return Inertia::location($url);
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
