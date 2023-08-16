<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:33:30 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant;

use App\Actions\Auth\User\StoreUser;
use App\Actions\Elasticsearch\CreateElasticSearchTenantAlias;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use App\Models\Auth\Role;
use App\Models\Tenancy\Tenant;
use Exception;
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
            'code'        => ['required', 'unique:tenants', 'between:1,6', 'alpha_dash'],
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
        return 'tenant:create {code} {email} {name} {username} {password} {country_code} {currency_code} {--l|language_code= : Language code} {--tz|timezone= : Timezone}';
    }

    public function asCommand(Command $command): int
    {
        try {
            $country = Country::where('code', $command->argument('country_code'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        try {
            $currency = Currency::where('code', $command->argument('currency_code'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        if ($command->option('language_code')) {
            try {
                $language = Language::where('code', $command->option('language_code'))->firstOrFail();
            } catch (Exception $e) {
                $command->error($e->getMessage());

                return 1;
            }
        } else {
            $language = Language::where('code', 'en-gb')->firstOrFail();
        }

        if ($command->option('timezone')) {
            try {
                $timezone = Timezone::where('name', $command->option('timezone'))->firstOrFail();
            } catch (Exception $e) {
                $command->error($e->getMessage());

                return 1;
            }
        } else {
            $timezone = Timezone::where('name', 'UTC')->firstOrFail();
        }


        $this->setRawAttributes([
            'code'        => $command->argument('code'),
            'name'        => $command->argument('name'),
            'email'       => $command->argument('email'),
            'username'    => $command->argument('username'),
            'password'    => $command->argument('password'),
            'country_id'  => $country->id,
            'currency_id' => $currency->id,
            'language_id' => $language->id,
            'timezone_id' => $timezone->id,
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

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
