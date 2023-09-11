<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:32:25 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Enums\CRM\Customer\CustomerStatusEnum;
use App\Models\CRM\Customer;
use App\Models\Tenancy\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class StoreCustomer
{
    use AsAction;
    use WithAttributes;

    private bool $asAction     = false;
    public int $hydratorsDelay = 0;


    /**
     * @throws Throwable
     */
    public function handle(Tenant|null $parent, array $customerData, array $customerAddressesData = []): Customer
    {
        $customer = DB::transaction(function () use ($customerData, $parent) {

            if($parent) {
                $customerData['tenant_id'] = $parent->id;
            }

            /** @var Customer $customer */
            $customer = Customer::create($customerData);
            if ($customer->reference == null) {
                $customer->update(
                    [
                        'reference' => rand()
                    ]
                );
            }
            $customer->stats()->create();

            return $customer;
        });

        $customer->location = $customer->getLocation();
        $customer->save();

        //        CustomerHydrateUniversalSearch::dispatch($customer);

        return $customer;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.customers.edit");
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
    public function asController(Shop $shop, ActionRequest $request): Customer
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($shop, $request->validated());
    }



    public function htmlResponse(Customer $customer): RedirectResponse
    {
        return Redirect::route('crm.shops.show.customers.show', [$customer->shop->slug, $customer->slug]);
    }


    /**
     * @throws Throwable
     */
    public function action(Shop $shop, array $objectData, array $customerAddressesData): Customer
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($shop, $validatedData, $customerAddressesData);
    }

    /**
     * @throws Throwable
     */
    public function asFetch(Shop $shop, array $customerData, array $customerAddressesData, int $hydratorsDelay = 60): Customer
    {
        $this->hydratorsDelay = $hydratorsDelay;

        return $this->handle($shop, $customerData, $customerAddressesData);
    }
}
