<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 15:58:05 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use App\Actions\Auth\User\SendLinkResetPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class PasswordResetLink
{
    use AsController;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(ActionRequest $request): array
    {
        $token = Str::random(64);
        $email = $request->input('email');

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $email,
        ], [
            'email' => $request->input('email'),
            'token' => $token,
            'created_at' => now()
        ]);

        SendLinkResetPassword::run($token, $email);

        return [
            'status' => 200,
            'email' => [trans(Password::RESET_LINK_SENT)],
        ];
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
            'email' => ['required', 'email', 'exists:users'],
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function asController(ActionRequest $request): array
    {
        $request->validate();

        return $this->handle($request);
    }
}
