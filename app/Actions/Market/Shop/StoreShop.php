<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop;

use App\Actions\Accounting\PaymentAccount\StorePaymentAccount;
use App\Actions\Helpers\Query\Seeders\ProspectQuerySeeder;
use App\Actions\Mail\Outbox\SeedShopOutboxes;
use App\Actions\Market\Shop\Hydrators\ShopHydrateUniversalSearch;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateShops;
use App\Enums\Helpers\SerialReference\SerialReferenceModelEnum;
use App\Enums\Market\Shop\ShopTypeEnum;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreShop
{
    use AsAction;
    use WithAttributes;
    private bool $asAction = false;

    public function handle(Organisation $organisation, array $modelData = []): Shop
    {
        data_set($modelData, 'code', $organisation->code, overwrite: false);
        data_set($modelData, 'name', $organisation->name, overwrite: false);
        data_set($modelData, 'country_id', $organisation->country_id, overwrite: false);
        data_set($modelData, 'language_id', $organisation->language_id, overwrite: false);
        data_set($modelData, 'timezone_id', $organisation->timezone_id, overwrite: false);
        data_set($modelData, 'currency_id', $organisation->currency_id, overwrite: false);


        /** @var Shop $shop */
        $shop = $organisation->shops()->create($modelData);
        $shop->stats()->create();
        $shop->accountingStats()->create();
        $shop->crmStats()->create();
        $shop->catalogueStats()->create();
        $shop->portfoliosStats()->create();
        $shop->mailStats()->create();

        $shop->serialReferences()->create(
            [
                'model' => SerialReferenceModelEnum::CUSTOMER,
            ]
        );

        $shop->serialReferences()->create(
            [
                'model' => SerialReferenceModelEnum::ORDER,
            ]
        );

        $shop->serialReferences()->create(
            [
                'model' => SerialReferenceModelEnum::INVOICE,
            ]
        );


        $paymentAccount       = StorePaymentAccount::run($organisation->accountsServiceProvider(), [
            'code' => 'accounts-'.$shop->slug,
            'name' => 'Accounts '.$shop->code,
            'data' => [
                'service-code' => 'accounts'
            ]
        ]);
        $paymentAccount->slug = 'accounts-'.$shop->slug;
        $paymentAccount->save();

        ProspectQuerySeeder::run($shop);

        $shop = AttachPaymentAccountToShop::run($shop, $paymentAccount);

        OrganisationHydrateShops::run();
        ShopHydrateUniversalSearch::dispatch($shop);
        SeedShopOutboxes::run($shop);

        return $shop;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.edit");
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($request->get('identity_document_number') and !$request->get('identity_document_type')) {
            $validator->errors()->add('contact_name', 'document type required');
        }
        if ($request->get('identity_document_type') and !$request->get('identity_document_number')) {
            $validator->errors()->add('contact_name', 'document number required');
        }
    }

    public function rules(): array
    {
        return [
            'name'                     => ['required', 'string', 'max:255'],
            'code'                     => ['required', 'iunique:shops', 'between:2,6', 'alpha_dash'],
            'contact_name'             => ['nullable', 'string', 'max:255'],
            'company_name'             => ['nullable', 'string', 'max:255'],
            'email'                    => ['nullable', 'email'],
            'phone'                    => 'nullable',
            'identity_document_number' => ['nullable', 'string'],
            'identity_document_type'   => ['nullable', 'string'],
            'type'                     => ['required', Rule::in(ShopTypeEnum::values())],
            'country_id'               => ['sometimes', 'required', 'exists:countries,id'],
            'currency_id'              => ['sometimes', 'required', 'exists:currencies,id'],
            'language_id'              => ['sometimes', 'required', 'exists:languages,id'],
            'timezone_id'              => ['sometimes', 'required', 'exists:timezones,id'],
        ];
    }

    public function asController(ActionRequest $request): Shop
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle(\organisation(), $request->validated());
    }

    public function htmlResponse(Shop $shop): RedirectResponse
    {
        return Redirect::route('org.shops.show', $shop->slug);
    }

    public function action($modelData): Shop
    {
        $this->asAction=true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle(organisation(), $validatedData);
    }


    public string $commandSignature = 'shop:create {code} {name} {type}';

    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        $this->setRawAttributes([
            'code' => $command->argument('code'),
            'name' => $command->argument('name'),
            'type' => $command->argument('type'),

        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $organisation = $this->handle(organisation(), $validatedData);

        $command->info("Organisation $organisation->code created successfully 🎉");

        return 0;
    }
}
