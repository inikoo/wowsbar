<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateUsers;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\User;
use Lorisleiva\Actions\ActionRequest;

class UpdateUserStatus
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(User $user, bool $status): User
    {
        $user->update(
            [
                'status' => $status
            ]
        );
        CustomerHydrateUsers::dispatch($user->customer);

        return $user;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can('sysadmin.edit');
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

        return $this->handle($user, $request->validated());
    }

    public function action(User $user, bool $status): User
    {
        $this->asAction = true;

        return $this->handle($user, $status);
    }

    public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }
}
