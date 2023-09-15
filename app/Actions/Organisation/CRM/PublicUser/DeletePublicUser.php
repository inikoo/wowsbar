<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\PublicUser;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\User;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;

class DeletePublicUser
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(User $user, array $modelData): User
    {
        return $this->update($user, $modelData, 'settings');
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }
        return  $request->user()->can('sysadmin.edit');

    }

    public function rules(): array
    {
        return [
            'username' => ['sometimes', 'required', new AlphaDashDot(), 'unique:users,username'],
            'password' => ['sometimes', 'required', app()->isLocal()  || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => 'sometimes|required|email|unique:users,email'
        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($request->exists('username') and $request->get('username') != strtolower($request->get('username'))) {
            $validator->errors()->add('invalid_username', 'Username must be lowercase.');
        }
    }


    public function asController(User $user, ActionRequest $request): User
    {
        $request->validate();
        return $this->handle($user, $request->validated());
    }

    public function action(User $user, $objectData): User
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($user, $validatedData);

    }

    public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }
}
