<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 30 Sept 2022 11:54:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources;

use App\Models\Auth\Guest;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachJobPosition
{
    use AsAction;


    public function handle(\App\Models\HumanResources\Employee|Guest $model, \App\Models\HumanResources\JobPosition $jobPosition): void
    {
        $model->jobPositions()->attach($jobPosition->id);

        $model->user?->assignJoBPositionRoles($jobPosition);
    }
}
