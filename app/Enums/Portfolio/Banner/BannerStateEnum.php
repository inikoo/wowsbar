<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Banner;

use App\Enums\EnumHelperTrait;

enum BannerStateEnum: string
{
    use EnumHelperTrait;

    case UNPUBLISHED = 'unpublished';
    case LIVE        = 'live';
    case RETIRED     = 'retired';

    public static function labels(): array
    {
        return [
            'in-process' => __('In process'),
            'ready'      => __('Ready'),
            'live'       => __('Live'),
            'retired'    => __('Retired'),

        ];
    }

    public static function count(): array
    {
        $stats = app('currentTenant')->portfolioStats;

        return [
            'in-process' => $stats->number_banners_state_in_process,
            'ready'      => $stats->number_banners_state_ready,
            'live'       => $stats->number_banners_state_live,
            'retired'    => $stats->number_banners_state_retired,
        ];
    }


}
