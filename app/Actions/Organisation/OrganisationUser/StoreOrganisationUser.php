<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser;

use App\Actions\Organisation\OrganisationUser\UI\SetOrganisationUserAvatar;
use App\Models\Organisation\Guest;
use App\Models\Organisation\OrganisationUser;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreOrganisationUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(Guest $parent, array $objectData = []): OrganisationUser
    {
        /** @var \App\Models\Organisation\OrganisationUser $organisationUser */
        $organisationUser =$parent->organisationUser()->create($objectData);
        $organisationUser->stats()->create();
        SetOrganisationUserAvatar::run($organisationUser);

        // UserHydrateUniversalSearch::dispatch($organisationUser);
         //OrganisationHydrateUsers::dispatch();
        return $organisationUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("sysadmin.edit");
    }

    public function rules(): array
    {
        return [
            'username' => ['required', new AlphaDashDot(), 'unique:org_users,username', Rule::notIn(['export', 'create'])],
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => ['sometimes','required', 'email', 'unique:org_users,email']
        ];
    }

    public function action(array $objectData = []): OrganisationUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();
        return $this->handle($validatedData);
    }


}
