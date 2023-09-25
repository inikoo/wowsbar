<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Sep 2023 14:23:19 Malaysia Time, plane Bali-KL
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Models\HumanResources\Workplace;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateWorkplaces
{
    use AsAction;

    public function handle(): void
    {

        $stats = [
            'number_workplaces' => Workplace::count()
        ];

        organisation()->humanResourcesStats()->update($stats);
    }
}
