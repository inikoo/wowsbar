<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 09:53:48 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\PortfolioSocialAccount;

use App\Enums\EnumHelperTrait;

enum PortfolioSocialAccountPostStatusEnum: string
{
    use EnumHelperTrait;

    case CREATING  = 'creating';
    case ON_HOLD   = 'on_hold';
    case NEED_HELP = 'need_help';
    case FINISHED  = 'finished';
}
