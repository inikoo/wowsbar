<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 11:54:26 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Announcement;

use App\Enums\EnumHelperTrait;

enum AnnouncementStateEnum: string
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
}
