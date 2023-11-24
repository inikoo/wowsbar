<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Helpers\Query\HydrateModelTypeQueries;
use App\Actions\Leads\Prospect\Hydrators\ProspectHydrateUniversalSearch;
use App\Actions\Market\Shop\Hydrators\ShopHydrateProspects;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateProspects;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectSuccessStatusEnum;
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
        dd($modelData);

        //$addressData = Arr::get($modelData, 'address');
        Arr::forget($modelData, 'address');
        //if ($addressData) {
        //todo
        //UpdateAddress::run($prospect->getAddress('contact'),$addressData);
        //$prospect->location = $prospect->getLocation();
        //$prospect->save();
        //}


        $prospect = $this->update($prospect, $modelData, ['data']);

        if ($prospect->wasChanged(['state', 'dont_contact_me', 'contacted_state', 'fail_status', 'success_status'])) {
            if ($prospect->scope_type == 'Shop') {
                OrganisationHydrateProspects::dispatch();
                ShopHydrateProspects::dispatch($prospect->scope);
            }
        }
        ProspectHydrateUniversalSearch::dispatch($prospect);
        HydrateModelTypeQueries::dispatch('Prospect')->delay(now()->addSeconds(5));

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
            'contacted_state'   => ['sometimes', Rule::enum(ProspectContactedStateEnum::class)],
            'fail_status'       => ['sometimes', 'nullable', Rule::enum(ProspectFailStatusEnum::class)],
            'success_status'    => ['sometimes', 'nullable', Rule::enum(ProspectSuccessStatusEnum::class)],
            'dont_contact_me'   => ['sometimes', 'boolean'],
            'last_contacted_at' => 'sometimes|nullable|date',
            'contact_name'      => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_name'      => ['sometimes', 'nullable', 'string', 'max:255'],
            'email'             => [
                'sometimes',
                'email',
                'max:500',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),

            ],
            'phone'             => [
                'sometimes',
                'nullable',
                'phone:AUTO',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),
            ],
            'contact_website'   => [
                'sometimes',
                'nullable',
                'url:http,https',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),
            ],
        ];
    }


    public function inShop(Shop $shop, Prospect $prospect, ActionRequest $request): Prospect
    {
        $this->scope     = $shop;
        $this->currentID = $prospect->id;
        $this->fillFromRequest($request);

        return $this->handle($prospect, $this->validateAttributes());
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
