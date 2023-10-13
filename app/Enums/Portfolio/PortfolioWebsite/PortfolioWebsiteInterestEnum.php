<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 18:32:34 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\PortfolioWebsite;

use App\Enums\EnumHelperTrait;

enum PortfolioWebsiteInterestEnum: string
{
    use EnumHelperTrait;

    case NOT_SURE           = 'not_sure';
    case INTERESTED         = 'interested';
    case NOT_INTERESTED     = 'not_interested';
    case CUSTOMER           = 'customer';
}
