<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerWebsite;

use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateWelcomeStep;
use App\Actions\Market\Shop\Hydrators\ShopHydrateCustomerWebsites;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Actions\Portfolios\CustomerWebsite\Hydrators\CustomerWebsiteHydrateUniversalSearch;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Actions\Traits\WithPortfolioWebsiteAction;
use App\Models\CRM\Customer;
use App\Models\Organisation\Division;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolios\CustomerWebsite;
use App\Rules\IUnique;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreCustomerWebsite
{
    use AsAction;
    use WithAttributes;
    use WithPortfolioWebsiteAction;


    private bool $asAction = false;
    /**
     * @var \App\Models\CRM\Customer
     */
    private Customer $customer;


    public function handle(Customer $customer, array $modelData): CustomerWebsite
    {
        data_set($modelData, 'shop_id', $customer->shop_id);
        /** @var CustomerWebsite $customerWebsite */
        $customerWebsite = $customer->customerWebsites()->create($modelData);

        $portfolioWebsite=PortfolioWebsite::find($customerWebsite->id);
        $this->createAudit($portfolioWebsite);

        $customerWebsite->stats()->create();

        CustomerHydratePortfolioWebsites::dispatch($customerWebsite->customer);

        $customerWebsite->divisions()->attach(Division::pluck('id'));

        PortfolioWebsiteHydrateUniversalSearch::dispatch($portfolioWebsite);
        CustomerWebsiteHydrateUniversalSearch::dispatch($customerWebsite);

        OrganisationHydrateCustomerWebsites::dispatch();
        ShopHydrateCustomerWebsites::dispatch($customer->shop);

        CustomerHydrateWelcomeStep::make()->websiteAdded($customer);
        return $customerWebsite;
    }




    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->get('url')) {
            $request->merge(
                [
                    'url' => 'https://'.$request->get('url'),
                ]
            );
        }
    }


    public function rules(): array
    {
        return [
            'url'  => [
                'required',
                'url',
                'max:500',
                new IUnique(
                    table: 'portfolio_websites',
                    extraConditions: [
                        ['column' => 'customer_id', 'value' => $this->customer->id],
                    ]
                ),
            ],
            'name' => ['required', 'string', 'max:128']
        ];
    }

    public function asController(Customer $customer, ActionRequest $request): CustomerWebsite
    {
        $this->customer = $customer;
        $request->validate();

        return $this->handle($customer, $request->validated());
    }

    public function htmlResponse(CustomerWebsite $customerWebsite): RedirectResponse
    {
        return Redirect::route('org.crm.shop.customers.show.customer-websites.show', [
            $customerWebsite->customer->shop->slug,
            $customerWebsite->customer->slug,
            $customerWebsite->slug
        ]);
    }

    public function action(Customer $customer, array $objectData): CustomerWebsite
    {
        $this->customer = $customer;
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'customer:new-portfolio-website {customer} {url} {name}';
    }

    public function asCommand(Command $command): int
    {
        $this->asAction = true;
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception) {
            $command->error('Customer not found');

            return 1;
        }

        $this->customer = $customer;
        Config::set('global.customer_id', $customer->id);

        $this->setRawAttributes(
            [
                'url'  => $command->argument('url'),
                'name' => $command->argument('name')
            ]
        );
        $validatedData = $this->validateAttributes();

        $customerWebsite = $this->handle($customer, $validatedData);

        $command->info("Done! website $customerWebsite->slug created 🥳");

        return 0;
    }
}
