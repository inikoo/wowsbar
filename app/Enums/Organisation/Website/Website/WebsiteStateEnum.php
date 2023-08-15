<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Website\Website;

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
