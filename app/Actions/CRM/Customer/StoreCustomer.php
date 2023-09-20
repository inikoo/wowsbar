<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateUniversalSearch;
use App\Actions\Helpers\SerialReference\GetSerialReference;
use App\Enums\Helpers\SerialReference\SerialReferenceModelEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class StoreCustomer
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    /**
     * @throws Throwable
     */
    public function handle(Shop $shop, array $modelData, array $customerAddressesData = []): Customer
    {
        return DB::transaction(function () use ($modelData, $shop) {
            $organisation = organisation();

//            data_set($modelData, 'ulid', Str::ulid());
            data_set($modelData, 'timezone_id', $organisation->timezone_id, overwrite: false);
            data_set($modelData, 'language_id', $organisation->language_id, overwrite: false);

            /** @var Customer $customer */
            $customer = $shop->customers()->create($modelData);
            if ($customer->reference == null) {
                $reference = GetSerialReference::run(
                    container: $shop,
                    modelType: SerialReferenceModelEnum::CUSTOMER
                );
                $customer->update(
                    [
                        'reference' => $reference
                    ]
                );
            }
            $customer->generateSlug();
            $customer->stats()->create();
            $customer->portfolioStats()->create();

            CustomerHydrateUniversalSearch::dispatch($customer);

            return $customer;
        });
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("org.crm.edit");
    }

    public function rules(): array
    {
        return [
            'contact_name'             => ['nullable', 'string', 'max:255'],
            'company_name'             => ['nullable', 'string', 'max:255'],
            'email'                    => ['nullable', 'email'],
            'phone'                    => ['nullable', 'phone:AUTO'],
            'identity_document_number' => ['nullable', 'string'],
            'contact_website'          => ['nullable', 'active_url'],
            'timezone_id'              => ['nullable', 'exists:timezones,id'],
            'language_id'              => ['nullable', 'exists:languages,id']

        ];
    }

    public function afterValidator(Validator $validator): void
    {
        if (!$this->get('contact_name') and !$this->get('company_name')) {
            $validator->errors()->add('company_name', 'contact name or company name required');
        }
    }

    /**
     * @throws Throwable
     */
    public function asController(Shop $shop, ActionRequest $request): Customer
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($shop, $request->validated());
    }

    /**
     * @throws Throwable
     */
    public function action(Shop $shop, $objectData, array $customerAddressesData = []): Customer
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($shop, $validatedData, $customerAddressesData);
    }

    public string $commandSignature = 'shop:new-customer {shop} {email} {--N|contact_name=} {--C|company=} {--P|password=}';

    /**
     * @throws \Throwable
     */
    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        try {
            $shop = Shop::where('slug', $command->argument('shop'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }


        $this->setRawAttributes([
            'contact_name' => $command->option('contact_name'),
            'company_name' => $command->option('company'),
            'email'        => $command->argument('email'),
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $customer = $this->handle($shop, $validatedData);

        $command->info("Customer $customer->slug created successfully 🎉");

        return 0;
    }
}
