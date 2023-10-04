<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Leads\Prospect\Hydrators\ProspectHydrateUniversalSearch;
use App\Actions\Market\Shop\Hydrators\ShopHydrateProspects;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateProspects;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateProspects;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Portfolio\PortfolioWebsite;
use App\Rules\IUnique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProspect
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Shop|PortfolioWebsite $scope, array $modelData, array $addressesData = []): Prospect
    {
        if (class_basename($scope) == 'PortfolioWebsite') {
            data_set($modelData, 'customer_id', $scope->customer_id);
            data_set($modelData, 'portfolio_website_id', $scope->id);
        } elseif (class_basename($scope) == 'Shop') {
            data_set($modelData, 'shop_id', $scope->id);
        }

        /** @var Prospect $prospect */
        $prospect = $scope->scopedProspects()->create($modelData);


        if (class_basename($scope) == 'PortfolioWebsite') {
            PortfolioWebsiteHydrateProspects::dispatch();

        } elseif (class_basename($scope) == 'Shop') {
            ProspectHydrateUniversalSearch::dispatch($prospect);
            OrganisationHydrateProspects::dispatch();
            ShopHydrateProspects::dispatch($scope);
        }


        return $prospect;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.prospects.edit");
    }

    public function inShop(Shop $shop, ActionRequest $request): Prospect
    {
        $this->fillFromRequest($request);
        $request->validate();
        return $this->handle($shop, $request->validated());
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->get('contact_website')) {
            $request->merge(
                [
                    'contact_website' => 'https://'.$request->get('contact_website'),
                ]
            );
        }
    }

    public function rules(ActionRequest $request): array
    {
        $extraConditions=match($request->route()->getName()){
            'org.models.shop.prospects.store'=>[
                ['column' => 'shop_id', 'value' => $request->route()->parameters()['shop']],
            ],
            default=> []
        };


        return [
            'contact_name'    => ['nullable',  'string', 'max:255'],
            'company_name'    => ['nullable',  'string', 'max:255'],
            'email'           => ['required_without:phone','email', 'max:500',
                                  new IUnique(
                                      table: 'prospects',
                                      extraConditions:$extraConditions
                                  ),

            ],
            'phone'           => ['required_without:email', 'nullable', 'phone:AUTO',
                                  new IUnique(
                                      table: 'prospects',
                                      extraConditions:$extraConditions
                                  ),
                ],
            'contact_website' => ['nullable','iunique:prospects', 'active_url'],
        ];
    }

    public function action(Shop|PortfolioWebsite $scope, array $objectData, array $addressesData): Prospect
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($scope, $validatedData, $addressesData);
    }

    public function htmlResponse(Prospect $prospect): RedirectResponse
    {
        return Redirect::route('org.crm.shop.prospects.show', [
            $prospect->shop->slug,
            $prospect->slug
        ]);
    }

}
