<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User;

use App\Actions\Auth\User\StoreUser;
use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreOrgCustomerUser
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(Customer $customer, array $modelData = []): CustomerUser
    {
        return StoreUser::make()->action(
            customer: $customer,
            modelData: $modelData
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->trusted) {
            return true;
        }
        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function rules(): array
    {
        return [
            'password'       =>
                [
                    'sometimes',
                    'required',
                    app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()
                ],
            'contact_name'   => ['required', 'string', 'max:255'],
            'email'          => 'required|iunique:users|email|max:255',
            'is_root'        => ['sometimes', 'required', 'boolean'],
            'roles'          => ['sometimes', 'required', 'array'],
            'reset_password' => ['sometimes', 'boolean']
        ];
    }

    public function asController(Customer $customer, ActionRequest $request): CustomerUser
    {
        $request->validate();
        return $this->handle($customer, $request->validated());
    }

    public function htmlResponse(CustomerUser $customerUser): RedirectResponse
    {
        return Redirect::route('org.crm.shop.customers.show.customer-users.show', [
            $customerUser->customer->shop->slug,
            $customerUser->customer->slug,
            $customerUser->slug
        ]);
    }

    public function action(Customer $customer, array $objectData): CustomerUser
    {
        $this->trusted = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }


}
