<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Clocking;

use App\Actions\HumanResources\Clocking\Hydrators\ClockingHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Inventory\LocationResource;
use App\Models\HumanResources\Clocking;
use Lorisleiva\Actions\ActionRequest;

class UpdateClocking
{
    use WithActionUpdate;

    private bool $asAction=false;

    public function handle(Clocking $clocking, array $modelData): Clocking
    {
        $clocking =  $this->update($clocking, $modelData, ['data']);

        ClockingHydrateUniversalSearch::dispatch($clocking);
        //        HydrateClocking::run($clocking);

        return $clocking;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->asAction) {
            return true;
        }
        return $request->user()->hasPermissionTo("inventory.locations.edit");
    }
    public function rules(): array
    {
        return [
            'code'         => ['sometimes', 'required', 'unique:locations', 'between:2,64', 'alpha_dash'],
        ];
    }
    public function action(Clocking $clocking, array $objectData): Clocking
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($clocking, $validatedData);
    }

    public function asController(Clocking $clocking, ActionRequest $request): Clocking
    {
        $request->validate();
        return $this->handle($clocking, $request->validated());
    }

    public function jsonResponse(Clocking $clocking): LocationResource
    {
        return new LocationResource($clocking);
    }
}
