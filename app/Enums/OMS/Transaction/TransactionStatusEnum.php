<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:25:18 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\OMS\Transaction;

use App\Enums\EnumHelperTrait;

enum TransactionStatusEnum: string
{
    use EnumHelperTrait;


    case PROCESSING              = 'processing';
    case DISPATCHED              = 'settled-dispatched';
    case DISPATCHED_WITH_MISSING = 'settled-with-missing';
    case FAIL                    = 'settled-fail';
    case CANCELLED               = 'settled-cancelled';
}
