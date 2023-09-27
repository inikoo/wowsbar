<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\SocialAccount;

use App\Enums\EnumHelperTrait;

enum SocialAccountProviderEnum: string
{
    use EnumHelperTrait;

    case FACEBOOK         = 'facebook';
    case INSTAGRAM        = 'instagram';
    case TIKTOK           = 'tiktok';
}
