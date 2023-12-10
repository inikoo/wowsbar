<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Models\SysAdmin\Organisation;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateDepartments
{
    use AsAction;


    public function handle(Organisation $organisation): void
    {

        $stats            = [
            'number_departments' => $organisation->departments->count(),
        ];

        $organisation->catalogueStats->update($stats);
    }

    public function getJobUniqueId(Organisation $parameters): string
    {
        return $parameters->id;
    }
}
