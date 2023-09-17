<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateEmployees
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_employees' => Employee::count()
        ];

        array_merge($stats, $this->getEnumStats('employees', 'state', EmployeeStateEnum::class, Employee::class));
        organisation()->humanResourcesStats()->update($stats);
    }
}
