<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\RootUser;

use App\Actions\Auth\User\Hydrators\UserHydrateUniversalSearch;
use App\Actions\Auth\User\UI\SetUserAvatar;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateUsers;
use App\Models\Auth\RootUser;
use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreRootUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(Tenant $tenant, array $objectData = []): RootUser
    {
        /** @var User $rootUser */
        $rootUser = $tenant->rootUsers()->create($objectData);
        $rootUser->stats()->create();
        SetUserAvatar::run($rootUser);

        UserHydrateUniversalSearch::dispatch($rootUser);
        TenantHydrateUsers::dispatch(app('currentTenant'));

        return $rootUser;
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
            'username' => ['required', new AlphaDashDot(), 'unique:users,username', Rule::notIn(['export', 'create'])],
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => ['required', 'email', 'unique:users,email']
        ];
    }

    public function action(Tenant $tenant, ?array $objectData = []): RootUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($tenant, $validatedData);
    }


}
