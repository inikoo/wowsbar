<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 21:08:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\HumanResources\ClockingMachine\ClockingMachineTypeEnum;
use App\Models\HumanResources\ClockingMachine;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateClockingMachines
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {

        $stats = [
            'number_clocking_machines' => ClockingMachine::count()
        ];
        $stats=array_merge($stats, $this->getEnumStats('clocking_machines', 'type', ClockingMachineTypeEnum::class, ClockingMachine::class));

        organisation()->humanResourcesStats()->update($stats);
    }
}
