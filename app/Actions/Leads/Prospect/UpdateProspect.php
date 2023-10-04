<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Leads\Prospect\Hydrators\ProspectHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\CRM\ProspectResource;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Rules\IUnique;
use Lorisleiva\Actions\ActionRequest;

class UpdateProspect
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Shop $scope, Prospect $prospect, array $modelData): Prospect
    {
        $prospect = $this->update($prospect, $modelData, ['data']);
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
        $currentID = $request->route()->parameters()['prospect']->id;

        $extraConditions = match ($request->route()->getName()) {
            'org.models.shop.prospects.update' => [
                ['column' => 'shop_id', 'value' => $request->route()->parameters()['shop']],
                ['column' => 'id', 'operator' => '!=', 'value' => $currentID]

            ],
            default => [
                ['column' => 'id', 'operator' => '!=', 'value' => $currentID]
            ]
        };

        return [
            'contact_name'    => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_name'    => ['sometimes', 'nullable', 'string', 'max:255'],
            'email'           => [
                'sometimes',
                'required_without:phone',
                'email',
                'max:500',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),

            ],
            'phone'           => [
                'sometimes',
                'required_without:email',
                'nullable',
                'phone:AUTO',
                new IUnique(
                    table: 'prospects',
                    extraConditions: $extraConditions
                ),
            ],
            'contact_website' => ['sometimes', 'nullable', 'iunique:prospects', 'active_url'],
        ];
    }


    public function inShop(Shop $shop, Prospect $prospect, ActionRequest $request): Prospect
    {
        $request->validate();

        return $this->handle($shop, $prospect, $request->validated());
    }

    public function action(Shop $scope, Prospect $prospect, $objectData): Prospect
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($scope, $prospect, $validatedData);
    }

    public function jsonResponse(Prospect $prospect): ProspectResource
    {
        return new ProspectResource($prospect);
    }
}
