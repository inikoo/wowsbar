<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:18:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Helpers\SerialReference;

use App\Enums\EnumHelperTrait;

enum SerialReferenceModelEnum: string
{
    use EnumHelperTrait;

    case CUSTOMER         = 'customer';
    case ORDER            = 'order';
    case INVOICE          = 'invoice';

}
