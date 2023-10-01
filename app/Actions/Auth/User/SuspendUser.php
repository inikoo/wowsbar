<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\CustomerUser\UpdateCustomerUser;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\User;
use Lorisleiva\Actions\ActionRequest;

class SuspendUser
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(User $user): User
    {
        foreach ($user->customerUsers as $customerUser) {
            UpdateCustomerUser::run($customerUser, [
                'status' => false
            ]);
        }


        return $user;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo('sysadmin.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'boolean'],
        ];
    }


    public function asController(User $user, ActionRequest $request): User
    {
        $request->validate();

        return $this->handle($user);
    }

    public function action(User $user): User
    {
        $this->asAction = true;

        return $this->handle($user);
    }

    public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }
}
