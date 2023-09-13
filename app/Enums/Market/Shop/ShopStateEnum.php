<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 13:33:09 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Market\Shop;

use App\Enums\EnumHelperTrait;

enum ShopStateEnum: string
{
    use EnumHelperTrait;

    case IN_PROCESS   = 'in-process';
    case OPEN         = 'open';
    case CLOSING_DOWN = 'closing-down';
    case CLOSED       = 'closed';
}
