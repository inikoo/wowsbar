<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:32:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Helpers\SerialReference\GetSerialReference;
use App\Enums\Helpers\SerialReference\SerialReferenceModelEnum;
use App\Models\CRM\Customer;
use App\Models\Organisation\Organisation;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    public function handle(Organisation $organisation,array $customerData, array $customerAddressesData = []): Customer
    {
        return DB::transaction(function () use ($customerData,$organisation) {
            /** @var Customer $customer */
            $customer = Customer::create($customerData);
            if ($customer->reference == null) {

                $reference = GetSerialReference::run(
                    container: $organisation,
                    modelType: SerialReferenceModelEnum::CUSTOMER);
                $customer->update(
                    [
                        'reference' => $reference
                    ]
                );


            }
            $customer->generateSlug();
            $customer->stats()->create();

            // CustomerHydrateUniversalSearch::dispatch($customer);

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
    public function asController(ActionRequest $request): Customer
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle(organisation(),$request->validated());
    }

    /**
     * @throws Throwable
     */
    public function action(Organisation $organisation, $objectData, array $customerAddressesData=[]): Customer
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($organisation,$validatedData, $customerAddressesData);
    }

    public string $commandSignature = 'customer:create {email} {--N|contact_name=} {--C|company=} {--P|password=}';

    /**
     * @throws \Throwable
     */
    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        $this->setRawAttributes([
            'contact_name'        => $command->option('contact_name'),
            'company_name'        => $command->option('company'),
            'email'       => $command->argument('email'),
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $customer = $this->handle(organisation(),$validatedData);

        $command->info("Customer $customer->slug created successfully 🎉");

        return 0;
    }
}
