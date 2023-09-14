<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 07 Jun 2023 01:35:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Web\Webpage;

use App\Enums\EnumHelperTrait;

enum WebpageTypeEnum: string
{
    use EnumHelperTrait;


    case STOREFRONT    = 'storefront';
    case PRODUCT       = 'product';
    case CATEGORY      = 'category';
    case SHOPPING_CART = 'shopping-cart';
    case CONTENT       = 'content';
    case ENGAGEMENT    = 'engagement';
    case AUTH          = 'auth';

    case BLOG    = 'blog';
    case ARTICLE = 'article';

}
