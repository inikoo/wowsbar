<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CustomerWebsites\CustomerWebsite;

use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use App\Rules\CaseSensitive;
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


    private bool $asAction = false;


    public function handle(Customer $customer, array $modelData): PortfolioWebsite
    {
        $customerWebsite = $customer->customerWebsites()->create($modelData);
        $customerWebsite->stats()->create();
        CustomerHydratePortfolioWebsites::dispatch($customerWebsite->customer);
        OrganisationHydrateCustomerWebsites::dispatch();
        PortfolioWebsiteHydrateUniversalSearch::dispatch($customerWebsite);

        return $customerWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', new CaseSensitive('portfolio_websites')],
            'code'   => ['required', 'unique:portfolio_websites', 'max:8'],
            'name'   => ['required']
        ];
    }

    public function asController(ActionRequest $request): PortfolioWebsite
    {
        $request->validate();
        return $this->handle($request->validated());
    }

    public function htmlResponse(PortfolioWebsite $customerWebsite): RedirectResponse
    {
        return Redirect::route('customer.portfolio.websites.show', [
            $customerWebsite->slug
        ]);
    }

    public function action(array $objectData): PortfolioWebsite
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
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
        Config::set('global.customer_id', $customer->id);

        $this->setRawAttributes(
            [
                'domain' => $command->argument('domain'),
                'code'   => $command->argument('code'),
                'name'   => $command->argument('name')
            ]
        );
        $validatedData = $this->validateAttributes();

        $customerWebsite=$this->handle($validatedData);

        $command->info("Done! website $customerWebsite->code created 🥳");
        return 0;
    }
}
