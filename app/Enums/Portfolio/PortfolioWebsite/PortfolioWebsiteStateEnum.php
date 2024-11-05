<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 18:32:34 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\PortfolioWebsite;

use App\Enums\EnumHelperTrait;

enum PortfolioWebsiteStateEnum: string
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
}
