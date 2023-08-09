<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:45:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Landlord\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class LandlordEmailVerification
{
    use AsController;

    public function handle(ActionRequest $request): RedirectResponse
    {
        if ($request->user('landlord')->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user('landlord')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        return $this->handle($request);
    }
}
