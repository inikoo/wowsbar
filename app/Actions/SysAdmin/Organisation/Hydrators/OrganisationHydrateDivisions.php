<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 19:21:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Models\SysAdmin\Division;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateDivisions
{
    use AsAction;

    public function handle(): void
    {

        $stats = [
            'number_divisions' => Division::count()
        ];


        organisation()->taskStats()->update($stats);
    }
}
