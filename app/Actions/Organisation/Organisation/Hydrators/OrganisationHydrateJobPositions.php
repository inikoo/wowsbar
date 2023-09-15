<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 16:38:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Models\HumanResources\JobPosition;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateJobPositions
{
    use AsAction;

    public function handle(): void
    {

        $stats = [
            'number_job_positions' => JobPosition::count()
        ];


        organisation()->humanResourcesStats()->update($stats);
    }
}
