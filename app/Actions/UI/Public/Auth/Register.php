<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 15:58:05 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use App\Actions\Organisation\CRM\Customer\StoreCustomer;
use App\Actions\Tenant\Auth\User\StoreUser;
use App\Models\Auth\User;
use App\Models\Market\Shop;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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

    public function handle(Shop $shop, array $modelData): RedirectResponse
    {
        $customer = StoreCustomer::run(
            $shop,
            [
                'contact_name' => Arr::get($modelData, 'contact_name'),
                'email'        => Arr::get($modelData, 'email')

            ]
        );

        Config::set('customer_id', $customer->id);


        $user = StoreUser::run($customer, $modelData);


        event(new Registered($user));
        Auth::guard('public')->login($user);

        return redirect(RouteServiceProvider::HOME);
    }


    public function rules(): array
    {
        return [
            'contact_name' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:'.User::class,
            'email'        => 'required|string|email|max:255|unique:'.User::class,
            'password'     => ['required', 'confirmed', Rules\Password::defaults(), 'min:8'],
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(['username' => $request->get('email')]);
    }

    public function asController(ActionRequest $request): RedirectResponse
    {
        $request->validate();

        return $this->handle($request->get('website')->shop, $request->validated());
    }
}
