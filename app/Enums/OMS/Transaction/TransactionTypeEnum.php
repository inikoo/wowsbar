<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:25:18 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\OMS\Transaction;

use App\Enums\EnumHelperTrait;

enum TransactionTypeEnum: string
{
    use EnumHelperTrait;

    case ORDER  = 'order';
    case REFUND = 'refund';
}
