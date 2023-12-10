<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Sep 2023 14:23:19 Malaysia Time, plane Bali-KL
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Models\HumanResources\Workplace;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateWorkplaces
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {

        $stats = [
            'number_workplaces' => Workplace::count()
        ];
        $stats=array_merge($stats, $this->getEnumStats('workplaces', 'type', WorkplaceTypeEnum::class, Workplace::class));

        organisation()->humanResourcesStats()->update($stats);
    }
}
