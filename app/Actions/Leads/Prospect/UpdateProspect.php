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
use App\Actions\Traits\WithActionUpdate;
use App\Enums\CRM\Prospect\ProspectBounceStatusEnum;
use App\Enums\CRM\Prospect\ProspectContactStateEnum;
use App\Enums\CRM\Prospect\ProspectOutcomeStatusEnum;
use App\Http\Resources\CRM\ProspectResource;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Rules\IUnique;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateProspect
{
    use WithActionUpdate;
    use WithProspectPrepareForValidation;

    private bool $asAction = false;

    private Shop $scope;
    private int $currentID;

    public function handle(Prospect $prospect, array $modelData): Prospect
    {
        $addressData = Arr::get($modelData, 'address');
        Arr::forget($modelData, 'address');
        if ($addressData) {
            //todo
            //UpdateAddress::run($prospect->getAddress('contact'),$addressData);
            //$prospect->location = $prospect->getLocation();
            //$prospect->save();
        }


        $prospect = $this->update($prospect, $modelData, ['data']);

        if (true or $prospect->wasChanged(['state', 'dont_contact_me', 'contact_state', 'bounce_status', 'outcome_status'])) {
            if ($prospect->scope_type == 'Shop') {
                OrganisationHydrateProspects::dispatch();
                ShopHydrateProspects::dispatch($prospect->scope);
            }

        }
        ProspectHydrateUniversalSearch::dispatch($prospect);

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
        $extraConditions = match (class_basename($this->scope)) {
            'org.models.shop.prospects.update' => [
                ['column' => 'shop_id', 'value' => $this->scope->id],
                ['column' => 'id', 'operator' => '!=', 'value' => $this->currentID]

            ],
            default => [
                ['column' => 'id', 'operator' => '!=', 'value' => $this->currentID]
            ]
        };

        return [
            'contact_state'     => ['sometimes', Rule::enum(ProspectContactStateEnum::class)],
            'outcome_status'    => ['sometimes', 'nullable', Rule::enum(ProspectOutcomeStatusEnum::class)],
            'bounce_status'     => ['sometimes', 'nullable', Rule::enum(ProspectBounceStatusEnum::class)],
            'dont_contact_me'   => ['sometimes', 'boolean'],
            'last_contacted_at' => 'sometimes|date',
            'contact_name'      => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_name'      => ['sometimes', 'nullable', 'string', 'max:255'],
            'email'             => [
                'sometimes',
                'required_without:phone',
                'email',
                'max:500',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),

            ],
            'phone'             => [
                'sometimes',
                'required_without:email',
                'nullable',
                'phone:AUTO',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),
            ],
            'contact_website'   => ['sometimes', 'nullable', 'iunique:prospects', 'active_url'],
        ];
    }


    public function inShop(Shop $shop, Prospect $prospect, ActionRequest $request): Prospect
    {
        $this->scope     = $shop;
        $this->currentID = $prospect->id;
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($prospect, $request->validated());
    }

    public function action(Shop $scope, Prospect $prospect, $objectData): Prospect
    {
        $this->asAction  = true;
        $this->scope     = $scope;
        $this->currentID = $prospect->id;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($prospect, $validatedData);
    }

    public function jsonResponse(Prospect $prospect): ProspectResource
    {
        return new ProspectResource($prospect);
    }
}
