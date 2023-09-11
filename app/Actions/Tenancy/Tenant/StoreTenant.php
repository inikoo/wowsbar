<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:33:30 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant;

use App\Actions\Elasticsearch\CreateElasticSearchTenantAlias;
use App\Actions\Tenant\Auth\User\StoreUser;
use App\Models\Auth\Role;
use App\Models\CRM\Customer;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTenant
{
    use AsAction;
    use WithAttributes;

    public function handle(array $modelData, array $userData): Tenant
    {
        $tenant = Tenant::create($modelData);

        $tenant->stats()->create();
        $tenant->portfolioStats()->create();

        $tenant->execute(
            function (Tenant $tenant) use ($userData) {
                CreateElasticSearchTenantAlias::run();
                SetTenantLogo::run($tenant);
                $user = StoreUser::run($tenant, $userData);

                $superAdminRole = Role::where('guard_name', 'web')->where('name', 'super-admin')->firstOrFail();
                $user->assignRole($superAdminRole);
            }
        );

        return $tenant;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:tenants', 'between:1,12', 'alpha_dash'],
            'name'        => ['required', 'max:64'],
            'email'       => ['required', 'email', 'unique:tenants'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'country_id'  => ['required', 'exists:countries,id'],
            'language_id' => ['required', 'exists:languages,id'],
            'timezone_id' => ['required', 'exists:timezones,id'],
            'username'    => ['sometimes', 'string'],
            'password'    => ['sometimes', 'string'],

        ];
    }


    public function action($modelData): Tenant
    {
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle(
            Arr::except($validatedData, ['username', 'password']),
            array_merge(
                Arr::only($validatedData, [
                    'username',
                    'password',
                    'email'
                ]),
                [
                    'is_public' => true
                ]
            )
        );
    }

    public function getCommandSignature(): string
    {
        return 'tenant:create {code}';
    }

    public function asCommand(Command $command): int
    {
        $customer = Customer::where('slug', $command->argument('code'))->first();

        $this->setRawAttributes(array_merge($customer->toArray(), ['code' => $customer->slug]));
        $validatedData = $this->validateAttributes();

        $tenant = $this->handle(
            Arr::except($validatedData, ['username', 'password']),
            array_merge(
                Arr::only($validatedData, [
                    'username',
                    'password',
                    'email'
                ]),
                [
                    'is_public' => true
                ]
            )
        );

        $command->info("Tenant $tenant->slug created successfully 🎉");

        return 0;
    }


}
