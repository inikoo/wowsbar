<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 15:58:05 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use App\Actions\Organisation\CRM\Customer\StoreCustomer;
use App\Actions\Organisation\CRM\PublicUser\StorePublicUser;
use App\Models\CRM\PublicUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class Register
{
    use AsAction;
    use WithAttributes;
    use AsController;

    private bool $asAction = false;

    public function handle(array $modelData): RedirectResponse
    {

        $customer=StoreCustomer::run(
            organisation()->shop,
            [
                'contact_name'=> Arr::get($modelData, 'contact_name'),
                'email'       => Arr::get($modelData, 'email')

            ]
        );

        $publicUser = StorePublicUser::run($customer, $modelData);


        event(new Registered($publicUser));
        Auth::guard('public')->login($publicUser);
        return redirect(RouteServiceProvider::HOME);
    }

    public function rules(): array
    {
        return [
            'contact_name'     => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:'.PublicUser::class,
            'password'         => ['required', 'confirmed', Rules\Password::defaults(), 'min:8'],
        ];
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $request->validate();
        return $this->handle($request->validated());
    }
}
