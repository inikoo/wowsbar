<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Divisions;

use App\Enums\EnumHelperTrait;

enum DivisionEnum: string
{
    use EnumHelperTrait;
    case SEO             = 'seo';
    case PPC      = 'ppc';
    case SOCIAL = 'social';
    case PROSPECTS = 'prospects';
    case BANNERS = 'banners';
}
