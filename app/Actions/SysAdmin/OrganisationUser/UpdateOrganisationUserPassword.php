<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 16 Oct 2023 15:27:31 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Auth\OrganisationUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;

class UpdateOrganisationUserPassword
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(OrganisationUser $organisationUser, array $modelData): OrganisationUser
    {
        data_set($modelData, 'reset_password', false);
        return $this->update($organisationUser, $modelData, 'settings');
    }


    public function rules(): array
    {
        return [
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
        ];
    }


    public function asController(ActionRequest $request): OrganisationUser
    {
        $request->validate();

        return $this->handle($request->user('org'), $request->validated());
    }

    public function action(OrganisationUser $organisationUser, $objectData): OrganisationUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($organisationUser, $validatedData);
    }

    public function htmlResponse(): RedirectResponse
    {
        Session::put('reloadLayout', '1');

        return Redirect::route('org.dashboard.show');
    }
}
