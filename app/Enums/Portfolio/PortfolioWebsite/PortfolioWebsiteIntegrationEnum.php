<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Dec 2023 19:12:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\PortfolioWebsite;

use App\Enums\EnumHelperTrait;

enum PortfolioWebsiteIntegrationEnum: string
{
    use EnumHelperTrait;

    case AURORA = 'aurora';
    case NONE   = 'none';

}
