<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 11:54:26 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Web\Webpage;

use App\Enums\EnumHelperTrait;
use App\Models\SysAdmin\Organisation;
use App\Models\Web\Webpage;
use App\Models\Web\Website;

enum WebpageStateEnum: string
{
    use EnumHelperTrait;

    case IN_PROCESS = 'in-process';
    case READY      = 'ready';

    case LIVE   = 'live';
    case CLOSED = 'closed';


    public static function labels(): array
    {
        return [
            'in-process' => __('In construction'),
            'ready'      => __('Ready'),
            'live'       => __('Live'),
            'closed'     => __('Closed'),
        ];
    }

    public static function count(Website|Webpage|Organisation $parent): array
    {
        $webStats = match (class_basename($parent)) {
            'Organisation' => $parent->stats
        };

        return [
            'in-process' => $webStats->number_webpagea_state_in_process,
            'ready'      => $webStats->number_webpages_state_ready,
            'live'       => $webStats->number_webpages_state_live,
            'closed'     => $webStats->number_webpages_state_closed,
        ];
    }


}
