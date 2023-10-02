<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation;

use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use App\Models\Organisation\Organisation;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreOrganisation
{
    use AsAction;
    use WithAttributes;

    public function handle(array $modelData): Organisation
    {
        if (Organisation::count() > 0) {
            abort(419, 'Can not create more than one organisation');
        }

        $organisation = Organisation::create($modelData);
        $organisation->stats()->create();
        $organisation->humanResourcesStats()->create();
        $organisation->crmStats()->create();
        $organisation->catalogueStats()->create();
        $organisation->portfoliosStats()->create();

        AttachImageToOrganisation::run(
            organisation: $organisation,
            collection: 'logo',
            imagePath: resource_path('art/logo/logo-charcoal.png'),
            originalFilename: 'logo.png'
        );

        AttachImageToOrganisation::run(
            organisation: $organisation,
            collection: 'logo_white',
            imagePath: resource_path('art/logo/logo-white.png'),
            originalFilename: 'logo.png'
        );


        Artisan::call("db:seed --force --class=StockImageSeeder");
        Artisan::call("db:seed --force --class=JobPositionSeeder");



        return $organisation;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:organisations', 'between:1,16', 'alpha_dash'],
            'name'        => ['required', 'max:64'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'country_id'  => ['required', 'exists:countries,id'],
            'language_id' => ['required', 'exists:languages,id'],
            'timezone_id' => ['required', 'exists:timezones,id'],


        ];
    }


    public function action($modelData): Organisation
    {
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'org:create {code} {name} {country_code} {currency_code} {--l|language_code= : Language code} {--tz|timezone= : Timezone}';
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
            'code'        => $command->argument('code'),
            'name'        => $command->argument('name'),
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

        $organisation = $this->handle($validatedData);

        $command->info("Organisation $organisation->code created successfully 🎉");

        return 0;
    }


}
