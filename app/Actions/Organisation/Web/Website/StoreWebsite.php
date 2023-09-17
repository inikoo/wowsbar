<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 13:45:07 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website;

use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateWebsites;
use App\Models\Organisation\Market\Shop;
use App\Models\Organisation\Web\Website;
use App\Rules\CaseSensitive;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreWebsite
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;
    private Shop $shop;


    public function handle(Shop $shop, array $modelData): Website
    {
        data_set($modelData, 'organisation_id', organisation()->id);
        data_set($modelData, 'code', $shop->code, overwrite: false);
        data_set($modelData, 'name', $shop->name, overwrite: false);

        /** @var Website $website */
        $website = $shop->website()->create($modelData);
        $website->webStats()->create();
        OrganisationHydrateWebsites::run();

        $website->refresh();
        $website = ResetWebsiteStructure::run($website);

        return SeedWebsiteFixedWebpages::run($website);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', new CaseSensitive('websites')],
        ];
    }

    public function asController(Shop $shop, ActionRequest $request): Website
    {
        $this->shop = $shop;
        $request->validate();

        return $this->handle($shop, $request->validated());
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($this->shop->website) {
            $validator->errors()->add('domain', 'This organisation already have a website');
        }
    }


    public function action(Shop $shop, array $objectData): Website
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($shop, $validatedData);
    }

    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('org.website.show');
    }

    public string $commandSignature = 'shop:create-website {shop} {domain}';

    public function asCommand(Command $command): int
    {
        $this->asAction = true;


        try {
            $shop = Shop::where('slug', $command->argument('shop'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $this->shop = $shop;
        $this->setRawAttributes([
            'domain' => $command->argument('domain'),

        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $website= $this->handle($shop, $validatedData);

        $command->info("Website $website->domain created successfully 🎉");

        return 0;
    }
}
