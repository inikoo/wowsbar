<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Clocking\UI;

use App\Models\HumanResources\Clocking;
use Lorisleiva\Actions\Concerns\AsObject;

class GetClockingShowcase
{
    use AsObject;

    public function handle(Clocking $clocking): array
    {
        return [
            [

            ]
        ];
    }
}
