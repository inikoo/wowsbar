<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 16 Sep 2023 15:29:03 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Market\Shop;

use App\Enums\EnumHelperTrait;

enum ShopStateEnum: string
{
    use EnumHelperTrait;

    case IN_PROCESS   = 'in-process';
    case OPEN         = 'open';
    case CLOSING_DOWN = 'closing-down';
    case CLOSED       = 'closed';
}
