<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateJobPositionsShare;
use App\Actions\HumanResources\JobPosition\HydrateJobPosition;
use App\Models\Auth\Guest;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachJobPosition
{
    use AsAction;

    // todo transform EmployeeJobPosition to a polymorphic stuff
    public function handle(Employee|Guest $model, JobPosition $jobPosition): void
    {
        $model->jobPositions()->attach($jobPosition->id);
        $model->organisationUser?->assignJoBPositionRoles($jobPosition);

        if(class_basename($model)=='Employee'){
            EmployeeHydrateJobPositionsShare::dispatch($model);
            HydrateJobPosition::dispatch($jobPosition);
        }


    }
}
