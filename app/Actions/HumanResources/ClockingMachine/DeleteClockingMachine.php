<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteClockingMachine
{
    use AsController;
    use WithAttributes;

    public function handle(ClockingMachine $clockingMachine): ClockingMachine
    {
        $clockingMachine->clockings()->delete();
        $clockingMachine->delete();

        return $clockingMachine;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function asController(ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $request->validate();

        return $this->handle($clockingMachine);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWorkplace(Workplace $workplace, ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $request->validate();

        return $this->handle($clockingMachine);
    }



    public function htmlResponse(ClockingMachine $clockingMachine): RedirectResponse
    {
        return Redirect::route('org.hr.workplaces.show', $clockingMachine->workplace->slug);
    }



}
