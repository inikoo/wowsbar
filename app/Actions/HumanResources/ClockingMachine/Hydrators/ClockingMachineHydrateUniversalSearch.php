<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine\Hydrators;

use App\Models\HumanResources\ClockingMachine;
use Lorisleiva\Actions\Concerns\AsAction;

class ClockingMachineHydrateUniversalSearch
{
    use AsAction;

    public function handle(ClockingMachine $clockingMachine): void
    {
        $clockingMachine->universalSearch()->updateOrCreate(
            [],
            [
                'in_organisation' => true,
                'section'         => 'hr',
                'title'           => $clockingMachine->code,
                'description'     => $clockingMachine->workplace->name
            ]
        );
    }

}
