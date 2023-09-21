<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Leads\Prospect\Hydrators\ProspectHydrateUniversalSearch;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateProspects;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateProspects;
use App\Models\CRM\Customer;
use App\Models\CRM\Prospect;
use App\Models\Market\Shop;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProspect
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Shop|Customer|PortfolioWebsite $scope, array $modelData, array $addressesData = []): Prospect
    {
        if (class_basename($scope) == 'PortfolioWebsite') {
            data_set($modelData, 'customer_id', $scope->customer_id);
            data_set($modelData, 'portfolio_website_id', $scope->id);
        } elseif (class_basename($scope) == 'Customer') {
            data_set($modelData, 'customer_id', $scope->id);
            data_set($modelData, 'portfolio_website_id', $scope->id);
        } elseif (class_basename($scope) == 'Shop') {
            data_set($modelData, 'shop_id', $scope->id);
        }

        /** @var \App\Models\CRM\Prospect $prospect */
        $prospect = $scope->scopedProspects()->create($modelData);


        if (class_basename($scope) == 'PortfolioWebsite') {
        } elseif (class_basename($scope) == 'Customer') {

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

        return $request->user()->hasPermissionTo("shops.customers.edit");
    }

    public function rules(): array
    {
        return [
            'contact_name'    => ['required', 'nullable', 'string', 'max:255'],
            'company_name'    => ['required', 'nullable', 'string', 'max:255'],
            'email'           => ['required', 'nullable', 'email'],
            'phone'           => ['required', 'nullable', 'phone:AUTO'],
            'contact_website' => ['required', 'nullable', 'active_url'],
        ];
    }

    public function action(array $objectData, array $addressesData): Prospect
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData, $addressesData);
    }

}
