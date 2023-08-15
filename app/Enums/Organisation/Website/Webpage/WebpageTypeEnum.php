<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Website\Webpage;

use App\Enums\EnumHelperTrait;

enum WebpageTypeEnum: string
{
    use EnumHelperTrait;


    case STOREFRONT = 'storefront';
    case PRODUCT    = 'product';
    case CATEGORY   = 'category';
    case BASKET     = 'basket';
    case CHECKOUT   = 'checkout';
    case SHOP_INFO  = 'shop-info';
    case ENGAGEMENT = 'engagement';


}
