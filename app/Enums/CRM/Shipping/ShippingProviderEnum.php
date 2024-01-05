<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 21 Jun 2023 08:44:31 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Shipping;

use App\Enums\EnumHelperTrait;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;

enum ShippingProviderEnum: string
{
    use EnumHelperTrait;

    case DHL  = 'dhl';
    case FEDEX  = 'fedex';
    case SICEPAT  = 'sicepat';
}
