<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:45:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Landlord\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class LandlordPasswordResetLink
{
    use AsController;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(ActionRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $request->validate();

        return $this->handle($request);
    }
}
