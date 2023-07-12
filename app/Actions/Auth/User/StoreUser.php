<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\User\Hydrators\UserHydrateUniversalSearch;
use App\Actions\Auth\User\UI\SetUserAvatar;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateUsers;
use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(Tenant $tenant, array $objectData = []): User
    {
        /** @var User $user */
        $user = $tenant->users()->create($objectData);
        $user->stats()->create();
        SetUserAvatar::run($user);

        UserHydrateUniversalSearch::dispatch($user);
        TenantHydrateUsers::run(app('currentTenant'));
        return $user;
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
            'username' => ['required', new AlphaDashDot(), 'unique:App\Models\SysAdmin\SysUser,username', Rule::notIn(['export', 'create'])],
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => ['required', 'email', 'unique:App\Models\SysAdmin\SysUser,email']
        ];
    }

    public function action(Tenant $tenant, ?array $objectData = []): User
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($tenant, $validatedData);
    }


}
