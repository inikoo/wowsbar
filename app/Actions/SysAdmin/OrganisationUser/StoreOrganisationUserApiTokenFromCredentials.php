<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Dec 2023 17:06:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Illuminate\Validation\Validator;

class StoreOrganisationUserApiTokenFromCredentials
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(OrganisationUser $organisationUser, array $modelData): array
    {
        return [
            'token'=> $organisationUser->createToken(Arr::get($modelData, 'device_name', 'unknown-device'))->plainTextToken
            ];
    }


    public function rules(): array
    {
        return [
            'username'             => ['required', 'exists:organisation_users,username'],
            'password'             => ['required', 'string'],
            'device_name'          => ['required', 'string'],
            'organisation_user_id' => ['sometimes'],
        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $organisationUser = OrganisationUser::where('username', $request->get('username'))->first();


        if (!$organisationUser) {
            $validator->errors()->add('username', __('Wrong username.'));

            return;
        }

        if (!$organisationUser->status) {
            $validator->errors()->add('username', __('User is not active.'));

            return;
        }

        if (!Hash::check($this->get('password'), $organisationUser->password)) {
            $validator->errors()->add('password', __('Wrong password.'));

            return;
        }


        $this->fill([
            'organisation_user_id' => $organisationUser->id
        ]);
    }


    public function asController(ActionRequest $request): array
    {
        $this->fillFromRequest($request);

        $validatedData = $this->validateAttributes();

        $organisationUser = OrganisationUser::find($this->get('organisation_user_id'));

        return $this->handle($organisationUser, Arr::only($validatedData, ['device_name']));
    }
}
