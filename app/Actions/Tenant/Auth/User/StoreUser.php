<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\User;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateUsers;
use App\Actions\Tenant\Auth\User\Hydrators\UserHydrateUniversalSearch;
use App\Actions\Tenant\Auth\User\UI\SetUserAvatar;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;
use App\Rules\AlphaDashDot;
use Illuminate\Console\Command;
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

    public string $commandSignature = 'user:create {tenant} {username} {password} {role?}';

    public function handle(Tenant $tenant, array $objectData = []): User
    {
        /** @var User $user */
        $user = $tenant->users()->create($objectData);
        $user->stats()->create();
        SetUserAvatar::run($user);

        UserHydrateUniversalSearch::dispatch($user);
        TenantHydrateUsers::dispatch(app('currentTenant'));
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
            'username' => ['required', new AlphaDashDot(), 'unique:users,username', Rule::notIn(['export', 'create'])],
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => ['required', 'email', 'unique:users,email']
        ];
    }

    public function action(Tenant $tenant, ?array $objectData = []): User
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($tenant, $validatedData);
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->first();
        $tenant->makeCurrent();

        $user = $this->handle($tenant, [
            'username' => $command->argument('username'),
            'password' => $command->argument('password')
        ]);

        $superAdminRole = Role::where('guard_name', 'web')->where('name', 'super-admin')->firstOrFail();
        $user->assignRole($superAdminRole);

        echo "Damn! 🥳 u successfully add 1 user to " . $tenant->slug . "\n";
    }
}
