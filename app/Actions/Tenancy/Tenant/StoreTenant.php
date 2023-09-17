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
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTenant
{
    use AsAction;
    use WithAttributes;

    public function handle(Customer $customer, array $modelData): Tenant
    {
        $organisation = organisation();
        data_set($modelData, 'timezone_id', $organisation->timezone_id, overwrite: false);
        data_set($modelData, 'language_id', $organisation->language_id, overwrite: false);
        data_set($modelData, 'name', $customer->name, overwrite: false);
        data_set($modelData, 'email', $customer->email, overwrite: false);

        /** @var Tenant $tenant */
        $tenant = $customer->tenant()->create($modelData);

        $tenant->stats()->create();
        $tenant->portfolioStats()->create();


        $tenant->execute(
            function (Tenant $tenant) use ($customer) {
                CreateElasticSearchTenantAlias::run();
                SetTenantLogo::run($tenant);

                foreach ($customer->publicUsers as $publicUser) {
                    $username = strstr($publicUser->email, '@', true);

                    $userData       = [
                        'username' => $username,
                        'password' => app()->isProduction() ? wordwrap(Str::random(), 4, '-', true) : 'hello'
                    ];
                    $user           = StoreUser::run($tenant, $userData);
                    $superAdminRole = Role::where('guard_name', 'web')->where('name', 'super-admin')->firstOrFail();
                    $user->assignRole($superAdminRole);
                }
            }
        );

        return $tenant;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:tenants', 'between:1,12', 'alpha_dash'],
            'name'        => ['sometimes', 'required', 'max:64'],
            'email'       => ['sometimes', 'required', 'email', 'unique:tenants'],
            'language_id' => ['sometimes', 'required', 'exists:languages,id'],
            'timezone_id' => ['sometimes', 'required', 'exists:timezones,id'],
            //   'username'    => ['sometimes', 'string'],
            //   'password'    => ['sometimes', 'string'],

        ];
    }


    public function action(Customer $customer, $modelData): Tenant
    {
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'customer:new-tenant {customer} {code}';
    }

    public function asCommand(Command $command): int
    {
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception) {
            $command->error('Customer not found');

            return 1;
        }
        $data = [
            'code'  => $command->argument('code'),
            'name'  => $customer->name,
            'email' => $customer->email,
        ];

        $this->setRawAttributes($data);
        $validatedData = $this->validateAttributes();

        $tenant = $this->handle($customer, $validatedData);

        /*
          Arr::only($validatedData, [
              'username',
              'password',
              'email'
          ])
        );
*/


        $command->info("Tenant $tenant->slug created successfully 🎉");

        return 0;
    }


}
