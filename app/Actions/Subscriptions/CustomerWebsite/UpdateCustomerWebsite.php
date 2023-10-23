<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Subscriptions\CustomerWebsite;

use App\Actions\Subscriptions\CustomerWebsite\Hydrators\CustomerWebsiteHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Prospects\CustomerWebsiteResource;
use App\Models\CRM\Customer;
use App\Models\Portfolios\CustomerWebsite;
use App\Rules\IUnique;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;

class UpdateCustomerWebsite
{
    use WithActionUpdate;


    /**
     * @var \App\Models\CRM\Customer
     */
    private Customer $customer;

    public function handle(CustomerWebsite $customerWebsite, array $modelData): CustomerWebsite
    {
        $customerWebsite = $this->update($customerWebsite, $modelData, ['data']);
        CustomerWebsiteHydrateUniversalSearch::dispatch($customerWebsite);

        return $customerWebsite;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.edit");
    }


    public function rules(ActionRequest $request): array
    {
        $currentID = $request->route()->parameters()['customerWebsite']->id;

        return [
            'url' => [
                'sometimes',
                'url',
                'max:500',
                new IUnique(
                    table: 'portfolio_websites',
                    extraConditions: [
                        ['column' => 'customer_id', 'value' => $this->customer->id],
                        ['column' => 'id', 'operator' => '!=', 'value' => $currentID]
                    ]
                ),

            ],
            'name'          => ['sometimes', 'string', 'max:128'],
            'property_id'   => ['sometimes'],
            'google_ads_id' => ['sometimes']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->input('url')) {
            $request->replace([
                'url' => 'https://' . $request->input('url')
            ]);
        }
    }

    public function asController(Customer $customer, CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->customer = $customer;
        $request->validate();

        $modelData = [];
        foreach ($request->validated() as $key => $value) {
            data_set(
                $modelData,
                match ($key) {
                    'property_id'   => 'data.property_id',
                    'google_ads_id' => 'data.google_ads_id',
                    default         => $key
                },
                $value
            );

            data_set($modelData, 'url', Str::replace('https://', '', $request->input('url')));
        }

        return $this->handle($customerWebsite, $modelData);
    }

    public function jsonResponse(CustomerWebsite $customerWebsite): CustomerWebsiteResource
    {
        return new CustomerWebsiteResource($customerWebsite);
    }
}
