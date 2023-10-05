<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 28 Apr 2023 12:27:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Helpers\Interest;

use App\Enums\EnumHelperTrait;

enum InterestEnum: string
{
    use EnumHelperTrait;

    case NOT_SURE           = 'not_sure';
    case INTERESTED         = 'interested';
    case NOT_INTERESTED     = 'not_interested';
    case CUSTOMER           = 'customer';
}
