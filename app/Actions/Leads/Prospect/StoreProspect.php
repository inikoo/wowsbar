<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Helpers\Address\StoreAddressAttachToModel;
use App\Actions\Leads\Prospect\Hydrators\ProspectHydrateUniversalSearch;
use App\Actions\Market\Shop\Hydrators\ShopHydrateProspects;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateProspects;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateProspects;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Portfolio\PortfolioWebsite;
use App\Rules\IUnique;
use App\Rules\ValidAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProspect
{
    use AsAction;
    use WithAttributes;
    use WithProspectPrepareForValidation;

    private bool $asAction = false;
    /**
     * @var \App\Models\Market\Shop|\App\Models\Portfolio\PortfolioWebsite
     */
    private PortfolioWebsite|Shop $scope;

    public function handle(Shop|PortfolioWebsite $scope, array $modelData): Prospect
    {
        $addressData = Arr::get($modelData, 'address');
        Arr::forget($modelData, 'address');

        if (class_basename($scope) == 'PortfolioWebsite') {
            data_set($modelData, 'customer_id', $scope->customer_id);
            data_set($modelData, 'portfolio_website_id', $scope->id);
        } elseif (class_basename($scope) == 'Shop') {
            data_set($modelData, 'shop_id', $scope->id);
        }


        /** @var Prospect $prospect */
        $prospect = $scope->scopedProspects()->create($modelData);

        if ($addressData) {
            StoreAddressAttachToModel::run($prospect, $addressData, ['scope' => 'contact']);
            $prospect->location = $prospect->getLocation();
            $prospect->save();
        }
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
        $this->scope = $shop;
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($shop, $request->validated());
    }


    public function rules(ActionRequest $request): array
    {
        $extraConditions = match (class_basename($this->scope)) {
            'Shop' => [
                ['column' => 'shop_id', 'value' => $this->scope->id],
            ],
            default => []
        };


        return [
            'state'             => ['sometimes', new Enum(ProspectStateEnum::class)],
            'data'              => 'sometimes|array',
            'last_contacted_at' => 'sometimes|datetime',
            'created_at'        => 'sometimes|date',
            'address'           => ['sometimes', 'nullable', new ValidAddress()],
            'contact_name'      => ['nullable', 'string', 'max:255'],
            'company_name'      => ['nullable', 'string', 'max:255'],
            'email'             => [
                'required_without:phone',
                'email',
                'max:500',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),

            ],
            'phone'             => [
                'required_without:email',
                'nullable',
                'phone:AUTO',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),
            ],
            'contact_website'   => [
                'nullable',
                'url:http,https',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),
            ],
        ];
    }

    public function action(Shop|PortfolioWebsite $scope, array $objectData): Prospect
    {
        $this->scope    = $scope;
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();


        return $this->handle($scope, $validatedData);
    }

    public function htmlResponse(Prospect $prospect): RedirectResponse
    {
        return Redirect::route('org.crm.shop.prospects.show', [
            $prospect->shop->slug,
            $prospect->slug
        ]);
    }

}
