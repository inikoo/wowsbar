<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\WorkingPlace\Hydrators;

use App\Models\HumanResources\Workplace;
use Lorisleiva\Actions\Concerns\AsAction;

class WorkingPlaceHydrateUniversalSearch
{
    use AsAction;

    public function handle(Workplace $workplace): void
    {
        $workplace->universalSearch()->updateOrCreate(
            [],
            [
                'section' => 'hr',
                'title'   => $workplace->name,
            ]
        );
    }

}
