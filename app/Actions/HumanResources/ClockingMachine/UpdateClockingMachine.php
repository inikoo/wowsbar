<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Actions\HumanResources\ClockingMachine\Hydrators\ClockingMachineHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\HumanResources\ClockingMachineResource;
use App\Models\HumanResources\ClockingMachine;
use Lorisleiva\Actions\ActionRequest;

class UpdateClockingMachine
{
    use WithActionUpdate;

    public function handle(ClockingMachine $clockingMachine, array $modelData): ClockingMachine
    {
        $clockingMachine =  $this->update($clockingMachine, $modelData, ['data']);


        ClockingMachineHydrateUniversalSearch::dispatch($clockingMachine);

        return $clockingMachine;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function rules(): array
    {
        return [
            'name'  => ['sometimes','required'.'iunique:clocking_machines'],
        ];
    }

    public function asController(ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $request->validate();

        return $this->handle($clockingMachine, $request->validated());
    }

    public function jsonResponse(ClockingMachine $clockingMachine): ClockingMachineResource
    {
        return new ClockingMachineResource($clockingMachine);
    }
}
