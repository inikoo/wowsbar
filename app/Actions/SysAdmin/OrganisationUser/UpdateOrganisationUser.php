<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 09:38:55 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Auth\OrganisationUserResource;
use App\Models\Auth\OrganisationUser;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;

class UpdateOrganisationUser
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(OrganisationUser $organisationUser, array $modelData): OrganisationUser
    {


        return $this->update($organisationUser, $modelData, 'settings');
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
            'username'     => ['sometimes', 'required', new AlphaDashDot(), 'unique:organisation_users,username'],
            'password'     => ['sometimes', 'required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'status'       => 'sometimes|required|boolean'
        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($request->exists('username') and $request->get('username') != strtolower($request->get('username'))) {
            $validator->errors()->add('invalid_username', 'Username must be lowercase.');
        }
    }


    public function asController(OrganisationUser $organisationUser, ActionRequest $request): OrganisationUser
    {

        $request->validate();
        return $this->handle($organisationUser, $request->validated());
    }

    public function action(OrganisationUser $organisationUser, $objectData): OrganisationUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($organisationUser, $validatedData);

    }

    public function jsonResponse(OrganisationUser $organisationUser): OrganisationUserResource
    {
        return new OrganisationUserResource($organisationUser);
    }
}
