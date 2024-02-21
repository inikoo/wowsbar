<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 16 Oct 2023 15:27:31 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;

class UpdateUserPassword
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(User $user, array $modelData): User
    {
        data_set($modelData, 'reset_password', false);
        return $this->update($user, $modelData, 'settings');
    }


    public function rules(): array
    {
        return [
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'token'    => ['nullable', 'string']
        ];
    }


    public function asController(ActionRequest $request): User
    {
        $request->validate();

        if($request->input('token') !== null) {
            return $this->inUnAuth($request);
        }

        return $this->handle($request->user(), $request->validated());
    }

    public function inUnAuth(ActionRequest $request): User
    {
        $request->validate();

        $userModel = DB::table('password_reset_tokens')->where('token', $request->input('token'));
        /** @var User $user */
        $user = $userModel->first();
        $user = User::where('email', $user->email)->first();


        $response = $this->handle($user, $request->only('password'));
        $userModel->delete();

        return $response;
    }

    public function action(User $user, $objectData): User
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($user, $validatedData);
    }

    public function htmlResponse(): \Symfony\Component\HttpFoundation\Response
    {
        Session::put('reloadLayout', '1');
        return Inertia::location(route('customer.dashboard.show'));
    }
}
