<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateUniversalSearch;
use App\Actions\Market\Shop\Hydrators\ShopHydrateCustomers;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCustomers;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\CRM\CustomerResource;
use App\Models\CRM\Customer;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateCustomer
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Customer $customer, array $modelData): Customer
    {
        if (Arr::hasAny($modelData, ['contact_name', 'company_name'])) {
            $contact_name = Arr::exists($modelData, 'contact_name') ? Arr::get($modelData, 'contact_name') : $customer->contact_name;
            $company_name = Arr::exists($modelData, 'company_name') ? Arr::get($modelData, 'company_name') : $customer->company_name;

            $modelData['name'] = $company_name ?: $contact_name;
        }

        $customer = $this->update($customer, $modelData, ['data']);
        if ($customer->wasChanged(['status'])) {
            OrganisationHydrateCustomers::dispatch($customer);
            ShopHydrateCustomers::dispatch($customer->shop);
        }
        CustomerHydrateUniversalSearch::dispatch($customer);

        return $customer;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.customers.edit");
    }

    public function rules(): array
    {
        return [
            'contact_name'             => ['sometimes'],
            'company_name'             => ['sometimes'],
            'phone'                    => ['sometimes', 'nullable', 'phone:AUTO'],
            'identity_document_number' => ['sometimes', 'nullable', 'string'],
            'contact_website'          => ['sometimes', 'nullable', 'active_url'],
            'email'                    => ['sometimes', 'nullable', 'email']
        ];
    }

    public function asController(Customer $customer, ActionRequest $request): Customer
    {
        $request->validate();

        return $this->handle($customer, $request->validated());
    }


    public function jsonResponse(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }
}
