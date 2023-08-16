<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Banner;

use App\Enums\EnumHelperTrait;

enum BannerStateEnum: string
{
    use EnumHelperTrait;
    case IN_PROCESS = 'in-process';
    case READY      = 'ready';
    case LIVE       = 'live';
    case RETIRED    = 'retired';
}
