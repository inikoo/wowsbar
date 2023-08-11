<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:45:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class PublicVerifyEmail
{
    use AsController;

    public function handle(ActionRequest $request): RedirectResponse
    {
        if ($request->user('public')->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user('public')->markEmailAsVerified()) {
            event(new Verified($request->user('public')));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        return $this->handle($request);
    }
}
