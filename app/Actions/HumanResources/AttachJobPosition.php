<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources;

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
