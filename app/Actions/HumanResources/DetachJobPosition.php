<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources;

use App\Models\Auth\Guest;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use Lorisleiva\Actions\Concerns\AsAction;

class DetachJobPosition
{
    use AsAction;


    public function handle(Employee|Guest $model, JobPosition $jobPosition): void
    {
        $model->jobPositions()->detach($jobPosition->id);
        $model->organisationUser?->removeJoBPositionRoles($jobPosition);
    }
}
