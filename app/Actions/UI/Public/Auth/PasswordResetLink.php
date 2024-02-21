<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 15:58:05 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use App\Actions\Auth\User\SendLinkResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class PasswordResetLink
{
    use AsController;

    public function handle(ActionRequest $request): void
    {
        $token = Str::random(64);
        $email = $request->input('email');

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $email,
        ], [
            'email'      => $request->input('email'),
            'token'      => $token,
            'created_at' => now()
        ]);

        SendLinkResetPassword::run($token, $email);
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

    public function asController(ActionRequest $request): void
    {
        $request->validate();

        $this->handle($request);
    }
}
