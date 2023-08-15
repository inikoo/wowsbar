<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Website\Website;

use App\Enums\EnumHelperTrait;

enum WebsiteEngineEnum: string
{
    use EnumHelperTrait;

    case AURORA = 'aurora';
    case IRIS   = 'iris';
    case OTHER  = 'other';


    public static function labels(): array
    {
        return [
            'aurora' => 'Aurora',
            'iris'   => 'Iris',
            'other'  => __('Other'),
        ];
    }

    public static function count(): array
    {
        $webStats = app('currentTenant')->webStats;

        return [
            'aurora' => $webStats->number_websites_engine_aurora,
            'iris'   => $webStats->number_websites_engine_iris,
            'other'  => $webStats->number_websites_engine_other,
        ];
    }


}
