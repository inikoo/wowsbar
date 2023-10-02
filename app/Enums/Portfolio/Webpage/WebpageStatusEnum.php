<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Webpage;

use App\Enums\EnumHelperTrait;

enum WebpageStatusEnum: string
{
    use EnumHelperTrait;

    case SUCCESS = 'success';
    case FAILED  = 'failed';
}
