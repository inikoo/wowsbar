<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 21:08:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Models\HumanResources\ClockingMachine;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateClockings
{
    use AsAction;
    use WithEnumStats;

    public function handle(ClockingMachine $clockingMachine): void
    {

        $stats = [
            'number_clockings' => $clockingMachine->clockings()->count()
        ];

        $clockingMachine->stats()->update($stats);
    }
}
