<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 15 Jan 2024 12:28:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Clocking;

use App\Actions\HumanResources\TimeTracking\StoreTimeTracking;
use App\Actions\HumanResources\Workplace\Hydrators\WorkplaceHydrateClockings;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateClockings;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\HumanResources\Clocking\ClockingTypeEnum;
use App\Http\Resources\HumanResources\ClockingResource;
use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use App\Rules\PolyExist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreClockingFromClockingMachine
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(ClockingMachine $clockingMachine, array $modelData): Clocking
    {
        data_forget($modelData, 'nfc_tag');
        $modelData['workplace_id'] = $clockingMachine->workplace_id;
        $modelData['clocked_at']   = date('Y-m-d H:i:s');
        $modelData['type']         = ClockingTypeEnum::CLOCKING_MACHINE;

        data_set($modelData, 'generator_type', Arr::get($modelData, 'subject_type'));
        data_set($modelData, 'generator_id', Arr::get($modelData, 'subject_id'));


        /** @var Clocking $clocking */
        $clocking = $clockingMachine->clockings()->create($modelData);
        OrganisationHydrateClockings::dispatch();
        WorkplaceHydrateClockings::dispatch($clockingMachine->workplace);
        // ClockingMachineHydrateClockings::dispatch($clockingMachine);
        $timeTracking = StoreTimeTracking::run($clocking, $modelData);
        $this->update($clocking, ['time_tracking_id' => $timeTracking->id]);

        $middleClocking = Clocking::where('subject_type', $clocking->subject_type)
            ->where('subject_id', $clocking->subject_id)
            ->where('workplace_id', $clocking->workplace_id)
            ->where('id', '<>', $clocking->id)
            ->where('clocked_at', '>', $clocking->clocked_at);

        if ($middleClocking->count() >= 2) {
            $middleClocking->delete();
        }

        $clocking->refresh();

        return $clocking;
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
            'subject_type' => ['required', 'string', new PolyExist()],
            'subject_id'   => ['required', 'integer'],
            'nfc_tag'      => ['required', 'string']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fill(
            [
                'subject_type' => $request->user()->parent_type,
                'subject_id'   => $request->user()->parent_id
            ]
        );
    }

    public function asFromNfcTag(ActionRequest $request): Clocking
    {
        $this->fillFromRequest($request);
        $clockingMachine = ClockingMachine::whereJsonContains('data', ['nfc_tag' => $request->input('nfc_tag')])->firstOrFail();

        return $this->handle($clockingMachine, $this->validateAttributes());
    }

    public function asController(ClockingMachine $clockingMachine, ActionRequest $request): Clocking
    {
        $this->fillFromRequest($request);

        return $this->handle($clockingMachine, $this->validateAttributes());
    }

    public function asCommand(ClockingMachine $clockingMachine, ActionRequest $request): Clocking
    {
        $request->validate();

        return $this->handle($clockingMachine, $request->validated());
    }

    public function htmlResponse(Clocking $clocking): RedirectResponse
    {
        if (!$clocking->clocking_machine_id) {
            return Redirect::route('org.hr.workplaces.show.clockings.show', [
                $clocking->workplace->slug,
                $clocking->slug
            ]);
        } else {
            return Redirect::route('org.hr.workplaces.show.clocking-machines.show.clockings.show', [
                $clocking->workplace->slug,
                $clocking->clockingMachine->slug,
                $clocking->slug
            ]);
        }
    }

    public function action(ClockingMachine|Workplace $parent, array $objectData): Clocking
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }

    public function jsonResponse(Clocking $clocking): ClockingResource
    {
        return ClockingResource::make($clocking);
    }
}
