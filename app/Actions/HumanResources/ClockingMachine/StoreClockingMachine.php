<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Actions\HumanResources\ClockingMachine\Hydrators\ClockingMachineHydrateUniversalSearch;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreClockingMachine
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Workplace $workplace, array $modelData): ClockingMachine
    {
        /** @var \App\Models\HumanResources\ClockingMachine $clockingMachine */
        $clockingMachine =  $workplace->clockingMachines()->create($modelData);
        ClockingMachineHydrateUniversalSearch::dispatch($clockingMachine);
        return $clockingMachine;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("hr.edit");
    }


    public function rules(): array
    {
        return [
            'code'  => ['required', 'iunique:clocking_machines', 'between:2,64', 'alpha_dash'],
        ];
    }

    public function asController(Workplace $workplace, ActionRequest $request): ClockingMachine
    {
        $request->validate();

        return $this->handle($workplace, $request->validated());
    }

    public function htmlResponse(ClockingMachine $clockingMachine): RedirectResponse
    {
        return Redirect::route(
            'org.hr.workplaces.show.clocking-machines.show',
            [
                'workplace'       => $clockingMachine->workplace->slug,
                'clockingMachine' => $clockingMachine->slug
            ]
        );
    }

    public function action(Workplace $workplace, array $objectData): ClockingMachine
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($workplace, $validatedData);
    }
}
