<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\CRM\Prospect;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateProspects
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_prospects' => Prospect::count()
        ];

        array_merge($stats, $this->getEnumStats('prospects', 'state', ProspectStateEnum::class, Prospect::class));
        organisation()->crmStats()->update($stats);
    }
}
