<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 09 Oct 2023 08:24:40 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
    private string $home              = '/dashboard';
    private string $gate              = 'org';


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

            LogOrganisationUserFailLogin::dispatch(
                organisation: organisation(),
                credentials: $request->validated(),
                ip: request()->ip(),
                userAgent: $request->header('User-Agent'),
                datetime: now()
            );


            throw ValidationException::withMessages([
                $this->credentialHandler => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        /** @var \App\Models\Auth\OrganisationUser $organisationUser */
        $organisationUser = auth($this->gate)->user();

        LogOrganisationUserLogin::dispatch(
            organisation:organisation(),
            organisationUser:$organisationUser,
            ip: request()->ip(),
            userAgent: $request->header('User-Agent'),
            datetime: now()
        );

        $request->session()->regenerate();
        Session::put('reloadLayout', '1');



        $language = $organisationUser->language;
        if ($language) {
            app()->setLocale($language);
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
