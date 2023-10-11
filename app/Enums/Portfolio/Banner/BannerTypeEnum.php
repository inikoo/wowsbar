<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 10 Oct 2023 15:08:27 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Banner;

use App\Enums\EnumHelperTrait;

enum BannerTypeEnum: string
{
    use EnumHelperTrait;

    case LANDSCAPE = 'landscape';
    case PORTRAIT        = 'portrait';


    public static function labels(): array
    {
        return [
            'landscape' => __('landscape'),
            'portrait'        => __('portrait'),

        ];
    }

    public static function count(): array
    {
        $stats = customer()->portfolioStats;

        return [
            'landscape' => $stats->number_banners_type_landscape,
            'portrait'        => $stats->number_banners_type_portrait,
        ];
    }


}
