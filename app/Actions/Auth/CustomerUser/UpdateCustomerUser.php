<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 23:59:34 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser;

use App\Actions\Auth\User\UpdateUser;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateCustomerUsers;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Auth\CustomerUserResource;
use App\Models\Auth\CustomerUser;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;

class UpdateCustomerUser
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(CustomerUser $customerUser, array $modelData): CustomerUser
    {
        $customerUser = $this->update($customerUser, Arr::only($modelData, ['status']));

        if ($customerUser->wasChanged('status')) {
            CustomerHydrateCustomerUsers::run($customerUser->customer);
        }

        UpdateUser::run($customerUser->user, Arr::only($modelData, ['contact_name', 'email', 'password']));

        $customerUser->refresh();

        return $customerUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        if ($request->route()->parameters()['customerUser']->customer_id != customer()->id) {
            return false;
        }

        return $request->get('customerUser')->hasPermissionTo('sysadmin.edit');
    }

    public function rules(): array
    {
        return [
            'contact_name' => ['sometimes', 'required', 'max:255'],
            'email'        => ['sometimes', 'required', 'max:500', 'email', 'iunique:users,email'],
            'password'     => ['sometimes', 'required', 'max:255', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'status'       => 'sometimes|required|boolean'
        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($request->exists('username') and $request->get('username') != strtolower($request->get('username'))) {
            $validator->errors()->add('invalid_username', 'Username must be lowercase.');
        }
    }


    public function asController(CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $request->validate();

        return $this->handle($customerUser, $request->validated());
    }

    public function action(CustomerUser $customerUser, $objectData): CustomerUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customerUser, $validatedData);
    }

    public function jsonResponse(CustomerUser $customerUser): CustomerUserResource
    {
        return new CustomerUserResource($customerUser);
    }
}