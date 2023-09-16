<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 16 Sep 2023 15:29:03 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Market\Shop;

use App\Enums\EnumHelperTrait;

enum ShopTypeEnum: string
{
    use EnumHelperTrait;

    use EnumHelperTrait;

    case DIGITAL_MARKETING    = 'digital-marketing';
    case CONTENT_AS_A_SERVICE = 'content-as-a-service';


    public static function labels(): array
    {
        return [
            'digital-marketing'       => 'Digital Marketing',
            'content-as-a-service'    => 'Content as a service',

        ];
    }
}
