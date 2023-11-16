<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 16 Nov 2023 12:43:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Helpers\Fetch;

use App\Enums\EnumHelperTrait;

enum FetchTypeEnum: string
{
    use EnumHelperTrait;

    case PROSPECTS    = 'prospects';

}
