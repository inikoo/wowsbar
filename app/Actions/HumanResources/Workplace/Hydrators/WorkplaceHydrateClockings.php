<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 15 Jan 2024 14:22:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Workplace\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Models\HumanResources\Workplace;
use Lorisleiva\Actions\Concerns\AsAction;

class WorkplaceHydrateClockings
{
    use AsAction;
    use WithEnumStats;

    public function handle(Workplace $workplace): void
    {

        $stats = [
            'number_clockings' => $workplace->clockings()->count()
        ];

        $workplace->stats()->update($stats);
    }
}
