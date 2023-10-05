<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser;

use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateUsers;
use App\Actions\Organisation\OrganisationUser\Hydrators\OrganisationUserHydrateUniversalSearch;
use App\Actions\Organisation\OrganisationUser\UI\SetOrganisationUserAvatar;
use App\Models\Auth\Guest;
use App\Models\Auth\OrganisationUser;
use App\Models\HumanResources\Employee;
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


    public function handle(Employee|Guest $parent, array $objectData = []): OrganisationUser
    {
        /** @var \App\Models\Auth\OrganisationUser $organisationUser */
        $organisationUser = $parent->organisationUser()->create($objectData);
        $organisationUser->stats()->create();
        SetOrganisationUserAvatar::dispatch($organisationUser);

        OrganisationUserHydrateUniversalSearch::dispatch($organisationUser);
        OrganisationHydrateUsers::dispatch();
        return $organisationUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("sysadmin.edit");
    }

    public function rules(): array
    {
        return [
            'username'     => ['required', new AlphaDashDot(), 'unique:organisation_users,username', Rule::notIn(['export', 'create'])],
            'password'     => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'        => ['sometimes', 'nullable', 'email', 'unique:organisation_users,email'],
            'contact_name' => ['required', 'string', 'max:255'],

        ];
    }

    public function action(Employee|Guest $parent, array $objectData = []): OrganisationUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }
}
