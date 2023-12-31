<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\CustomerUser\StoreCustomerUser;
use App\Actions\Auth\User\Hydrators\UserHydrateUniversalSearch;
use App\Actions\Auth\User\UI\SetUserAvatar;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateUniversalSearch;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use Arr;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(Customer $customer, array $modelData = []): CustomerUser
    {
        data_set($modelData, 'ulid', Str::ulid());
        data_set($modelData, 'website_id', $customer->website_id);
        /** @var User $user */
        $user = User::create(Arr::except($modelData, ['is_root', 'roles']));

        /** @var CustomerUser $customerUser */
        $customerUser = StoreCustomerUser::run($customer, $user, Arr::only($modelData, ['is_root']));

        $customerUser->refresh();

        foreach (Arr::get($modelData, 'roles', []) as $roleName) {
            $role = Role::where('guard_name', 'customer')->where('name', $roleName)->first();
            if ($role) {
                $customerUser->assignRole($role);
            }
        }


        if (!$customer->website_id) {
            $customer->update(
                ['website_id' => Arr::get($modelData, 'website_id')]
            );
            CustomerHydrateUniversalSearch::run($customer);
        }

        $user->stats()->create();


        SetUserAvatar::run($user);
        UserHydrateUniversalSearch::dispatch($user);

        return $customerUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("sysadmin.edit");
    }

    public function rules(): array
    {
        return [
            'password'       =>
                [
                    'sometimes',
                    'required',
                    app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()
                ],
            'contact_name'   => ['required', 'string', 'max:255'],
            'email'          => 'required|iunique:users|email|max:255',
            'is_root'        => ['sometimes', 'required', 'boolean'],
            'roles'          => ['sometimes', 'required', 'array'],
            'reset_password' => ['sometimes', 'boolean'],
            'data'           => ['sometimes', 'array'],
        ];
    }

    public function asController(ActionRequest $request): CustomerUser
    {
        $request->validate();
        $customer = customer();


        return $this->handle($customer, $request->validated());
    }

    public function htmlResponse(CustomerUser $customerUser): RedirectResponse
    {
        return Redirect::route('customer.sysadmin.users.show', [
            $customerUser->slug
        ]);
    }

    public function action(Customer $customer, ?array $modelData = []): CustomerUser
    {
        $this->asAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }


    public function getCommandSignature(): string
    {
        return 'customer:new-user {customer} {email} {--P|password=} {--N|name=}';
    }

    public function asCommand(Command $command): int
    {
        $this->asAction = true;
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception) {
            $command->error('Customer not found');

            return 1;
        }

        if (!$website = $customer->shop->website) {
            $command->error('Shop dont have website');

            return 1;
        }


        Config::set('global.customer_id', $customer->id);

        $email = $command->argument('email');
        $name  = $command->option('name') ?? $customer->contact_name;

        $this->setRawAttributes(
            [
                'website_id'   => $website->id,
                'contact_name' => $name,
                'email'        => $email,
                'password'     => $command->option('password') ?? (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
            ]
        );

        $validatedData = $this->validateAttributes();
        $customerUser  = $this->handle($customer, $validatedData);


        $command->line("Public user $customerUser->slug created successfully");

        return 0;
    }
}
