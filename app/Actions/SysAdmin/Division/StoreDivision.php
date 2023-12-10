<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Division;

use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateDivisions;
use App\Models\SysAdmin\Division;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreDivision
{
    use AsAction;
    use WithAttributes;
    private bool $asAction=false;

    public function handle(array $modelData): Division
    {
        $division= Division::create($modelData);
        $division->taskStats()->create();
        OrganisationHydrateDivisions::run();
        return $division;
    }
}
