<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 09 Oct 2023 10:14:58 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Auth\User\Login;
use App\Actions\Auth\User\StoreUser;
use App\Models\Market\Shop;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rules\Password;
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


        $customerUser = StoreUser::run(
            $customer,
            array_merge(
                $modelData,
                [
                    'is_root' => true,
                    'roles'   => [
                        'super-admin'
                    ]
                ]
            )
        );


        event(new Registered($customerUser));
        Auth::guard('customer')->login($customerUser->user);
        Login::make()->logCustomerUser($customerUser->user);


        return redirect('app/dashboard');
    }


    public function rules(): array
    {
        return [
            'contact_name' => 'required|string|max:255',
            'email'        => ['required', 'email', 'iunique:customers'],
            'password'     => [
                'required',
                'confirmed',
                app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()
            ],
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
