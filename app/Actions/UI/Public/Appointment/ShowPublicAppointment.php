<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:40:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Appointment;

use App\Models\CRM\Appointment;
use App\Models\Web\Webpage;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowPublicAppointment
{
    use AsAction;

    public function handle(): array
    {
        $calendars = [];

        $appointments = Appointment::whereMonth('schedule_at', now()->format('m'))->get();

        foreach ($appointments as $appointment) {
            $calendars[$appointment->schedule_at->format('Y-m-d')][] = $appointment->schedule_at->format('H:i');
        }

        return $calendars;
    }

    public function htmlResponse(array $calendars): Response
    {
        $webpage = Webpage::where('slug', 'appointment')->first();
        return Inertia::render(
            'Webpage',
            [

                'content' => $webpage->compiled_layout,

            ]
        );
    }


}
