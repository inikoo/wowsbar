<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 24 Apr 2023 20:23:18 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\User;

use App\Actions\Tenancy\User\UI\SetUserAvatar;
use App\Models\Tenancy\Tenant;
use App\Models\Tenancy\User;
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

        return $user;
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
