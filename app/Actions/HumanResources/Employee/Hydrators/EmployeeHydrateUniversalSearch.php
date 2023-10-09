<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee\Hydrators;

use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class EmployeeHydrateUniversalSearch
{
    use AsAction;

    public function handle(Employee $employee): void
    {

        $employee->universalSearch()->updateOrCreate(
            [],
            [
                'in_organisation' => true,
                'section'         => 'hr',
                'title'           => join(' ', [
                    $employee->alias,
                    $employee->worker_number,
                    $employee->contact_name,
                ]),
                'description'     => join(' ', [
                    $employee->work_email,
                    $employee->job_title,
                    $employee->email
                ])

            ]
        );
    }


}
