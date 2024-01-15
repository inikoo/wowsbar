<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Actions\HumanResources\ClockingMachine\Hydrators\ClockingMachineHydrateUniversalSearch;
use App\Actions\HumanResources\Workplace\Hydrators\WorkplaceHydrateClockingMachines;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateClockingMachines;
use App\Enums\HumanResources\ClockingMachine\ClockingMachineTypeEnum;
use App\Http\Resources\HumanResources\ClockingMachineResource;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
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

        if (Arr::get($modelData, 'type') == ClockingMachineTypeEnum::STATIC_NFC->value) {
            data_set($modelData, 'data.nfc_tag', $this->get('nfc_tag'));
            Arr::forget($modelData, 'nfc_tag');
        }


        /** @var ClockingMachine $clockingMachine */
        $clockingMachine = $workplace->clockingMachines()->create($modelData);
        OrganisationHydrateClockingMachines::dispatch();
        WorkplaceHydrateClockingMachines::dispatch($workplace);
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
            'name'    => ['required', 'iunique:clocking_machines', 'max:64', 'string'],
            'type'    => ['required', Rule::enum(ClockingMachineTypeEnum::class)],
            'nfc_tag' => ['sometimes', 'string'],

        ];
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($this->get('type') == ClockingMachineTypeEnum::STATIC_NFC && empty($this->get('nfc_tag'))) {
            $validator->errors()->add('nfc_tag', 'Invalid NFC Tag');
        }
    }

    public function asController(Workplace $workplace, ActionRequest $request): ClockingMachine
    {
        $this->fillFromRequest($request);
        return $this->handle($workplace, $this->validateAttributes());
    }

    public function action(Workplace $workplace, array $objectData): ClockingMachine
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($workplace, $validatedData);
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

    public function jsonResponse(ClockingMachine $clockingMachine): ClockingMachineResource
    {
        return ClockingMachineResource::make($clockingMachine);
    }


}
