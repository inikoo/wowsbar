<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 09:38:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Marketing\Shop;

use App\Enums\EnumHelperTrait;

enum ShopStateEnum: string
{
    use EnumHelperTrait;

    case IN_PROCESS   = 'in-process';
    case OPEN         = 'open';
    case CLOSING_DOWN = 'closing-down';
    case CLOSED       = 'closed';
}
