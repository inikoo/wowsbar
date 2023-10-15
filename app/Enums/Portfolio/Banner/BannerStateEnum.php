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
            'unpublished' => __('Unpublished'),
            'live'        => __('Live'),
            'retired'     => __('Retired'),

        ];
    }
    public static function stateIcon(): array
    {
        return [
            'unpublished' => [

                'tooltip' => __('unpublished'),
                'icon'    => 'fal fa-seedling',
                'class'   => 'text-indigo-500'


            ],
            'live'        => [

                'tooltip' => __('live'),
                'icon'    => 'fal fa-broadcast-tower',
                'class'   => 'text-green-600 animate-pulse'

            ],
            'retired'     => [

                'tooltip' => __('retired'),
                'icon'    => 'fal fa-eye-slash'

            ],

        ];
    }

    public static function dateIcon(): array
    {
        return [
            'unpublished' => [

                'tooltip' => __('created'),
                'icon'    => 'fal fa-sparkles',
                'class'   => 'text-yellow-500'


            ],
            'live'        => [
                'tooltip' => __('published'),
                'icon'    => 'fal fa-rocket',

            ],
            'retired'     => [
                'tooltip' => __('retired'),
                'icon'    => 'fal fa-do-not-enter',
                'class'   => 'text-red-500'

            ],

        ];
    }


    public static function count(): array
    {
        $stats = customer()->portfolioStats;

        return [
            'unpublished' => $stats->number_banners_state_unpublished,
            'live'        => $stats->number_banners_state_live,
            'retired'     => $stats->number_banners_state_retired,
        ];
    }


}
