<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:24:56 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\OMS\Order;

use App\Enums\EnumHelperTrait;

enum OrderStatusEnum: string
{
    use EnumHelperTrait;

    case PROCESSING = 'processing';
    case DISPATCHED = 'settled-dispatched';
    case FAIL       = 'settled-fail';
    case CANCELLED  = 'settled-cancelled';
}
