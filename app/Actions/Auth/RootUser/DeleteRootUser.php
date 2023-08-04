<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 24 Apr 2023 20:23:18 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\RootUser;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\RootUser;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;

class DeleteRootUser
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(RootUser $rootUser, array $modelData): RootUser
    {
        return $this->update($rootUser, $modelData, 'settings');
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
            'username' => ['sometimes', 'required', new AlphaDashDot(), 'unique:root_users,username'],
            'password' => ['sometimes', 'required', app()->isLocal()  || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => 'sometimes|required|email|unique:root_users,email'
        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($request->exists('username') and $request->get('username') != strtolower($request->get('username'))) {
            $validator->errors()->add('invalid_username', 'Username must be lowercase.');
        }
    }


    public function asController(RootUser $rootUser, ActionRequest $request): RootUser
    {
        $request->validate();
        return $this->handle($rootUser, $request->validated());
    }

    public function action(RootUser $rootUser, $objectData): RootUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($rootUser, $validatedData);
    }

    public function jsonResponse(RootUser $rootUser): UserResource
    {
        return new UserResource($rootUser);
    }
}
