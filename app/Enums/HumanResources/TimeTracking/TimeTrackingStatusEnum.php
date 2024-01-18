<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 08 Jun 2023 23:19:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\HumanResources\TimeTracking;

use App\Enums\EnumHelperTrait;

enum TimeTrackingStatusEnum: string
{
    use EnumHelperTrait;

    case CREATING = 'creating';

    case IN = 'in';

    case OUT = 'out';

    case ERROR = 'error';


}
