<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\User\Hydrators\UserHydrateUniversalSearch;
use App\Actions\Auth\User\UI\SetUserAvatar;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateUniversalSearch;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateUsers;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Web\Website;
use App\Rules\AlphaDashDot;
use Arr;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
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


    public function handle(Website $website, Customer $customer, array $modelData = []): User
    {
        data_set($modelData, 'ulid', Str::ulid());
        data_set($modelData, 'website_id', $website->id);
        /** @var User $user */
        $user = $customer->users()->create($modelData);

        if (!$customer->website_id) {
            $customer->update(
                ['website_id' => Arr::get($modelData, 'website_id')]
            );
            CustomerHydrateUniversalSearch::run($customer);
        }

        $user->stats()->create();

        SetUserAvatar::run($user);

        UserHydrateUniversalSearch::dispatch($user);

        CustomerHydrateUsers::dispatch($customer);

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
            'username'     => ['required', new AlphaDashDot(), 'unique:users,username', Rule::notIn(['export', 'create']), 'min:4'],
            'password'     =>
                [
                    'sometimes',
                    'required',
                    app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()
                ],
            'contact_name' => ['required', 'string', 'max:255'],
            'email'        => 'required|string|email|max:255|unique:'.User::class,
        ];
    }

    public function asController(Website $website, Customer $customer, ActionRequest $request): User
    {
        $request->validate();

        return $this->handle($website, $customer, $request->validated());
    }

    public function htmlResponse(User $user): RedirectResponse
    {
        return Redirect::route('sysadmin.users.show', [
            $user->username
        ]);
    }

    public function action(Website $website, Customer $customer, ?array $modelData = []): User
    {
        $this->asAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $customer, $validatedData);
    }


    public function getCommandSignature(): string
    {
        return 'customer:new-user {customer} {--e|email=} {--u|username=} {--P|password=} {--N|name=}';
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

        $email    = $command->option('email')    ?? $customer->email;
        $username = $command->option('username') ?? $email;
        $name     = $command->option('name')     ?? $customer->contact_name;

        $this->setRawAttributes(
            [
                'website_id'   => $website->id,
                'contact_name' => $name,
                'email'        => $email,
                'username'     => $username,
                'password'     => $command->option('password') ?? (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
            ]
        );

        $validatedData = $this->validateAttributes();
        $user          = $this->handle($website, $customer, $validatedData);

        $superAdminRole = Role::where('guard_name', 'customer')->where('name', 'super-admin')->firstOrFail();
        $user->assignRole($superAdminRole);

        $command->line("Public user $user->email created successfully");

        return 0;
    }
}
