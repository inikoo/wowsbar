<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation;

use App\Actions\Organisation\Auth\Guest\StoreGuest;
use App\Actions\Organisation\Web\Website\StoreWebsite;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use App\Models\Auth\Role;
use App\Models\Organisation\Organisation;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreOrganisation
{
    use AsAction;
    use WithAttributes;

    public function handle(array $modelData, array $organisationUserData): Organisation
    {
        if (Organisation::count() > 0) {
            abort(419, 'Can not create more than one organisation');
        }

        $organisation = Organisation::create($modelData);
        $organisation->stats()->create();

        AttachImageToOrganisation::run(
            organisation: $organisation,
            collection: 'logo',
            imagePath: resource_path('images/logo.png'),
            originalFilename: 'logo.png'
        );

        AttachImageToOrganisation::run(
            organisation: $organisation,
            collection: 'logo_white',
            imagePath: resource_path('images/logo.png'),
            originalFilename: 'logo.png'
        );


        $guest = StoreGuest::run(
            [
                'type'         => GuestTypeEnum::EXTERNAL_ADMINISTRATOR,
                'company_name' => Arr::get($organisationUserData, 'company_name'),
                'contact_name' => Arr::get($organisationUserData, 'contact_name'),
                'username'     => Arr::get($organisationUserData, 'username')

            ]
        );


        $superAdminRole = Role::where('guard_name', 'org')->where('name', 'super-admin')->firstOrFail();
        $guest->organisationUser->assignRole($superAdminRole);

        Artisan::call("db:seed --force --class=StockImageSeeder");

        StoreWebsite::run([]);

        return $organisation;
    }

    public function rules(): array
    {
        return [
            'code'         => ['required', 'unique:organisations', 'between:1,16', 'alpha_dash'],
            'name'         => ['required', 'max:64'],
            'currency_id'  => ['required', 'exists:currencies,id'],
            'country_id'   => ['required', 'exists:countries,id'],
            'language_id'  => ['required', 'exists:languages,id'],
            'timezone_id'  => ['required', 'exists:timezones,id'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'username'     => ['sometimes', 'string'],
            'password'     => ['sometimes', 'string'],

        ];
    }


    public function action($modelData): Organisation
    {
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle(
            Arr::except($validatedData, ['username', 'password']),
            Arr::only($validatedData, [
                'username',
                'password',
                'email'
            ]),
        );
    }

    public function getCommandSignature(): string
    {
        return 'org:create {code} {email} {name} {contact_name} {username} {password} {country_code} {currency_code} {--l|language_code= : Language code} {--tz|timezone= : Timezone}';
    }


    public function asCommand(Command $command): int
    {
        if (Organisation::count() > 0) {
            $command->error('There is already one organisation (You can only have one 😝)');
            exit;
        }

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
            'code'         => $command->argument('code'),
            'name'         => $command->argument('name'),
            'email'        => $command->argument('email'),
            'username'     => $command->argument('username'),
            'password'     => $command->argument('password'),
            'company_name' => $command->argument('name'),
            'contact_name' => $command->argument('contact_name'),
            'country_id'   => $country->id,
            'currency_id'  => $currency->id,
            'language_id'  => $language->id,
            'timezone_id'  => $timezone->id,
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $organisation = $this->handle(
            Arr::except($validatedData, [
                'username',
                'password',
                'contact_name',
                'company_name',
            ]),
            Arr::only($validatedData, [
                'username',
                'password',
                'contact_name',
                'company_name',
                'email'
            ]),
        );

        $command->info("Organisation $organisation->code created successfully 🎉");

        return 0;
    }


}
