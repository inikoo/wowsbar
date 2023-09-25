<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CustomerWebsites\CustomerWebsite;

use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\CustomerWebsites\CustomerWebsite\Hydrators\CustomerWebsiteHydrateUniversalSearch;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Models\CRM\Customer;
use App\Models\CustomerWebsites\CustomerWebsite;
use App\Rules\CaseSensitive;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreCustomerWebsite
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;


    public function handle(Customer $customer, array $modelData): CustomerWebsite
    {
        /** @var CustomerWebsite $customerWebsite */
        $customerWebsite = $customer->customerWebsites()->create($modelData);
        $customerWebsite->stats()->create();
        CustomerHydratePortfolioWebsites::dispatch($customerWebsite->customer);
        OrganisationHydrateCustomerWebsites::dispatch();
        CustomerWebsiteHydrateUniversalSearch::dispatch($customerWebsite);

        return $customerWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("crm.edit");
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', new CaseSensitive('portfolio_websites')],
            'code'   => ['required', 'unique:portfolio_websites', 'max:8'],
            'name'   => ['required']
        ];
    }

    public function asController(Customer $customer, ActionRequest $request): CustomerWebsite
    {
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
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'customer:new-portfolio-website {customer} {domain} {code} {name}';
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

        $this->setRawAttributes(
            [
                'domain' => $command->argument('domain'),
                'code'   => $command->argument('code'),
                'name'   => $command->argument('name')
            ]
        );
        $validatedData = $this->validateAttributes();

        $customerWebsite=$this->handle($customer, $validatedData);

        $command->info("Done! website $customerWebsite->code created 🥳");
        return 0;
    }
}
