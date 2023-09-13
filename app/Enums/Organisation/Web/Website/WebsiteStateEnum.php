<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 18:35:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Web\Website;

use App\Enums\EnumHelperTrait;

enum WebsiteStateEnum: string
{
    use EnumHelperTrait;

    case IN_PROCESS = 'in-process';
    case LIVE       = 'live';
    case CLOSED     = 'closed';


    public static function labels(): array
    {
        return [
            'in-process' => __('In construction'),
            'live'       => __('Live'),
            'closed'     => __('Closed'),
        ];
    }

    public static function count(): array
    {
        $webStats=app('currentTenant')->webStats;
        return [
            'in-process' => $webStats->number_websites_state_in_process,
            'live'       => $webStats->number_websites_state_live,
            'closed'     => $webStats->number_websites_state_closed,
        ];
    }



}
