<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail\Outbox;

use App\Enums\EnumHelperTrait;

enum OutboxStateEnum: string
{
    use EnumHelperTrait;
    case IN_PROCESS = 'in_process';
    case ACTIVE     = 'active';
    case SUSPENDED  = 'suspended';
}
