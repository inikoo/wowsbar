<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 15:52:24 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Web\WebBlockType;

use App\Enums\EnumHelperTrait;

enum WebBlockTypeScopeEnum: string
{
    use EnumHelperTrait;

    case WEBSITE          = 'website';
    case WEBPAGE          = 'webpage';
    case WEBSITE_LAYOUTS  = 'website-layouts';

}
