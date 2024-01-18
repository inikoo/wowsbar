<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\TimeTracking;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\HumanResources\Clocking\ClockingTypeEnum;
use App\Enums\HumanResources\TimeTracking\TimeTrackingStatusEnum;
use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\TimeTracking;
use App\Models\HumanResources\Workplace;
use Beste\Clock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTimeTracking
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Clocking $parent, array $modelData = []): TimeTracking
    {
        data_forget($modelData, 'type');
        data_forget($modelData, 'generator_type');
        data_forget($modelData, 'generator_id');

        $modelData['workplace_id'] = $parent->workplace_id;

        $timeTracking = TimeTracking::where('subject_type', $modelData['subject_type'])
            ->where('subject_id', $modelData['subject_id'])
            ->where('workplace_id', $parent->workplace_id)
            ->first();

        if(!$timeTracking) {
            data_forget($modelData, 'clocked_at');

            /** @var TimeTracking $timeTracking */
            $timeTracking = TimeTracking::create($modelData);
        }

        if ($timeTracking && ($timeTracking->status === TimeTrackingStatusEnum::IN)) {
            $modelData['end_clocking_id'] = $parent->id;
            $modelData['ends_at'] = $modelData['clocked_at'];
            $modelData['status'] = TimeTrackingStatusEnum::OUT;
        } else if ($timeTracking && ($timeTracking->status === TimeTrackingStatusEnum::CREATING)) {
            $modelData['starts_at'] = $modelData['clocked_at'];
            $modelData['start_clocking_id'] = $parent->id;
            $modelData['status'] = TimeTrackingStatusEnum::IN;
        }

        if ($timeTracking) {
            data_forget($modelData, 'clocked_at');
            $timeTracking = $this->update($timeTracking, $modelData);
        }

        return $timeTracking;
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
            'generator_id' => ['required'],
        ];
    }

    public function asController(ClockingMachine|Workplace $parent, ActionRequest $request): Clocking
    {
        $request->validate();

        return $this->handle($parent, $request->validated());
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
}
