<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:22:44 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Customer;

use App\Enums\EnumHelperTrait;

enum CustomerStatusEnum: string
{
    use EnumHelperTrait;

    case PENDING_APPROVAL = 'pending-approval';
    case APPROVED         = 'approved';
    case REJECTED         = 'rejected';
    case BANNED           = 'banned';
}
