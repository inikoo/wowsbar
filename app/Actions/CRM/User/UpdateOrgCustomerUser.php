<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 17:02:07 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User;

use App\Actions\Auth\CustomerUser\UpdateCustomerUser;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Auth\CustomerUser;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;

class UpdateOrgCustomerUser
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(CustomerUser $customerUser, array $modelData): CustomerUser
    {
        return UpdateCustomerUser::make()->action(
            $customerUser,
            $modelData
        );
    }

    public function authorize(ActionRequest $request): bool
    {

        return $request->user()->hasPermissionTo('crm.edit');
    }

    public function rules(): array
    {
        return [
            'contact_name' => ['sometimes', 'required', 'max:255'],
            'email'        => ['sometimes', 'required', 'max:500', 'email', 'iunique:users,email'],
            'password'     => ['sometimes', 'required', 'max:255', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'status'       => 'sometimes|required|boolean',
            'roles'        => ['sometimes', 'required', 'array'],
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


}
