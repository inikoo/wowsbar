<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Landlord\UI\Auth;

use App\Actions\Auth\User\StoreUser;
use App\Models\Auth\RootUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class RegisterUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(array $modelData): RedirectResponse
    {
        $user = StoreUser::run(app('currentTenant'), $modelData);

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:'.RootUser::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function action(ActionRequest $request): RedirectResponse
    {
        $request->validate();

        return $this->handle($request->validated());
    }
}
