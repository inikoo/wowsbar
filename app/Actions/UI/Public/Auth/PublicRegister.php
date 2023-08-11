<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use App\Models\Auth\PublicUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class PublicRegister
{
    use AsAction;
    use WithAttributes;
    use AsController;

    private bool $asAction = false;

    public function handle(array $modelData): RedirectResponse
    {
        $user = PublicUser::create($modelData);
        event(new Registered($user));
        Auth::guard('public')->login($user);
        return redirect(RouteServiceProvider::HOME);
    }

    public function rules(): array
    {
        return [
            'contact_name'     => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:'.PublicUser::class,
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $request->validate();
        return $this->handle($request->validated());
    }
}
