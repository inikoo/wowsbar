<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\Market\Shop\Hydrators\ShopHydrateCustomerWebsites;
use App\Actions\Portfolios\CustomerWebsite\Hydrators\CustomerWebsiteHydrateUniversalSearch;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Models\Portfolios\CustomerWebsite;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioWebsite
{
    use AsAction;
    use WithAttributes;


    /**
     * @var true
     */
    private bool $asAction = false;


    public function handle(array $modelData): PortfolioWebsite
    {
        data_set($modelData, 'shop_id', customer()->shop_id);
        $portfolioWebsite = PortfolioWebsite::create($modelData);
        $portfolioWebsite->stats()->create();
        CustomerHydratePortfolioWebsites::dispatch($portfolioWebsite->customer);

        PortfolioWebsiteHydrateUniversalSearch::dispatch($portfolioWebsite);
        CustomerWebsiteHydrateUniversalSearch::dispatch(CustomerWebsite::find($portfolioWebsite->id));

        OrganisationHydrateCustomerWebsites::dispatch();
        ShopHydrateCustomerWebsites::dispatch(customer()->shop);

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
            'url'    => ['required', 'url', 'max:500'],
            'code'   => ['required', 'alpha_dash:ascii', 'iunique:portfolio_websites', 'max:16'],
            'name'   => ['required', 'string', 'max:128']
        ];
    }

    public function asController(ActionRequest $request): PortfolioWebsite
    {
        $request->validate();
        return $this->handle($request->validated());
    }

    public function htmlResponse(PortfolioWebsite $portfolioWebsite): RedirectResponse
    {
        return Redirect::route('customer.portfolio.websites.show', [
            $portfolioWebsite->slug
        ]);
    }

    public function action(array $objectData): PortfolioWebsite
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }


}
