<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 09:38:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Marketing\Shop;

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
