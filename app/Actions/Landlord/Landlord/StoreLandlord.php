<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Landlord\Landlord;

use App\Actions\Landlord\LandlordUser\StoreLandlordUser;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use App\Models\Auth\Role;
use App\Models\Landlord\Landlord;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreLandlord
{
    use AsAction;
    use WithAttributes;

    public function handle(array $modelData, array $landlordUserData): Landlord
    {
        if (Landlord::count() > 0) {
            abort(419, 'Can not create more than one landlord');
        }

        $landlord = Landlord::create($modelData);
        $landlord->stats()->create();


        $landlordUser   = StoreLandlordUser::run($landlordUserData);
        $superAdminRole = Role::where('guard_name', 'landlord')->where('name', 'super-admin')->firstOrFail();
        $landlordUser->assignRole($superAdminRole);


        return $landlord;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:landlords', 'between:1,16', 'alpha_dash'],
            'name'        => ['required', 'max:64'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'country_id'  => ['required', 'exists:countries,id'],
            'language_id' => ['required', 'exists:languages,id'],
            'timezone_id' => ['required', 'exists:timezones,id'],
            'username'    => ['sometimes', 'string'],
            'password'    => ['sometimes', 'string'],

        ];
    }


    public function action($modelData): Landlord
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
        return 'landlord:create {code} {email} {name} {username} {password} {country_code} {currency_code} {--l|language_code= : Language code} {--tz|timezone= : Timezone}';
    }

    public function asCommand(Command $command): int
    {
        if (Landlord::count() > 0) {
            $command->error('There is already one landlord (You can only have one 😝)');
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

        $landlord = $this->handle(
            Arr::except($validatedData, ['username', 'password']),
            Arr::only($validatedData, [
                'username',
                'password',
                'email'
            ]),
        );

        $command->info("Landlord $landlord->slug created successfully 🎉");

        return 0;
    }


}
