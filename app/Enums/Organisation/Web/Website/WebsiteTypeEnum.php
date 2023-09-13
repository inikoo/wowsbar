<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 18:35:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Web\Website;

use App\Enums\EnumHelperTrait;

enum WebsiteTypeEnum: string
{
    use EnumHelperTrait;

    case INFO         = 'info';
    case B2B          = 'b2b';
    case B2C          = 'b2c';
    case DROPSHIPPING = 'dropshipping';
    case FULFILMENT   = 'fulfilment';


    public static function labels(): array
    {
        return [
            'info'         => __('info'),
            'b2b'          => __('B2B'),
            'b2c'          => __('B2C'),
            'dropshipping' => __('dropshipping'),
            'fulfilment'   => __('fulfilment'),
        ];
    }

    public static function count(): array
    {
        $webStats = app('currentTenant')->webStats;

        return [
            'info'         => $webStats->number_websites_type_info,
            'b2b'          => $webStats->number_websites_type_b2b,
            'b2c'          => $webStats->number_websites_type_b2c,
            'dropshipping' => $webStats->number_websites_type_dropshipping,
            'fulfilment'   => $webStats->number_websites_type_fulfilment,
        ];
    }


}
