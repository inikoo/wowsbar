<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Thu, 25 May 2023 15:03:06 Central European Summer Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Actions\Organisation\HumanResources\ClockingMachine\UI;

use App\Models\HumanResources\ClockingMachine;
use Lorisleiva\Actions\Concerns\AsObject;

class GetClockingMachineShowcase
{
    use AsObject;

    public function handle(ClockingMachine $clockingMachine): array
    {
        return [
            [

            ]
        ];
    }
}
