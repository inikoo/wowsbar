<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateWelcomeStep;
use App\Actions\Market\Shop\Hydrators\ShopHydrateCustomerWebsites;
use App\Actions\Subscriptions\CustomerWebsite\Hydrators\CustomerWebsiteHydrateUniversalSearch;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Actions\Traits\WithPortfolioWebsiteAction;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\CRM\Customer;
use App\Models\SysAdmin\Division;
use App\Models\Portfolios\CustomerWebsite;
use App\Models\Portfolio\PortfolioWebsite;
use App\Rules\IUnique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioWebsite
{
    use AsAction;
    use WithAttributes;
    use WithPortfolioWebsiteAction;


    private bool $asAction = false;


    public function handle(Customer $customer, array $modelData): PortfolioWebsite
    {
        data_set($modelData, 'shop_id', $customer->shop_id);
        // Note customer_id added magically by a global scope
        $portfolioWebsite = PortfolioWebsite::create($modelData);
        $portfolioWebsite->stats()->create();


        $this->createAudit(CustomerWebsite::find($portfolioWebsite->id));

        // Must be run to the website layout to work
        CustomerHydratePortfolioWebsites::run($portfolioWebsite->customer);

        $portfolioWebsite->divisions()->attach(Division::pluck('id'));

        PortfolioWebsiteHydrateUniversalSearch::dispatch($portfolioWebsite);
        CustomerWebsiteHydrateUniversalSearch::dispatch(CustomerWebsite::find($portfolioWebsite->id));

        OrganisationHydrateCustomerWebsites::dispatch();
        ShopHydrateCustomerWebsites::dispatch(customer()->shop);
        CustomerHydrateWelcomeStep::make()->websiteAdded($customer);

        return $portfolioWebsite;
    }



    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");
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
                        ['column' => 'customer_id', 'value' => customer()->id],
                    ]
                ),
            ],
            'name' => ['required', 'string', 'max:128']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if (!$request->exists('name')) {
            $parse = parse_url($request->url());
            $name  = preg_replace("/^([a-zA-Z0-9].*\.)?([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z.]{2,})$/", '$2', $parse['host']);
            if ($name == '') {
                $name = 'website';
            }

            $request->merge(
                [
                    'name' => $name
                ]
            );
        }

        if ($request->get('url')) {
            $request->merge(
                [
                    'url' => 'https://'.$request->get('url'),
                ]
            );
        }
    }

    public function asController(ActionRequest $request): PortfolioWebsite
    {
        $customer   =$request->get('customer');
        $welcomeStep=Arr::get($customer, 'data.welcome_step');

        $request->validate();

        $portfolioWebpage= $this->handle($customer, $request->validated());

        if($welcomeStep==1) {
            $customer->update(
                [
                    'data->welcome_step'=> 2
                ]
            );
        }

        return $portfolioWebpage;
    }

    public function fromWelcome(ActionRequest $request): PortfolioWebsite
    {
        $request->validate();

        return $this->handle($request->get('customer'), $request->validated());
    }

    public function jsonResponse(PortfolioWebsite $portfolioWebsite): PortfolioWebsiteResource
    {
        return new PortfolioWebsiteResource($portfolioWebsite);
    }

    public function htmlResponse(PortfolioWebsite $portfolioWebsite): RedirectResponse
    {
        Session::put('reloadLayout', '1');


        return Redirect::route('customer.portfolio.websites.show', [
            $portfolioWebsite->slug
        ]);
    }

    public function action(Customer $customer, array $objectData): PortfolioWebsite
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }


}
