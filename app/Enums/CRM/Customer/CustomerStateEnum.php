<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:22:44 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Customer;

use App\Enums\EnumHelperTrait;

enum CustomerStateEnum: string
{
    use EnumHelperTrait;

    case REGISTERED = 'registered';

    case WITH_APPOINTMENT = 'with-appointment';

    case CONTACTED = 'contacted';

    case ACTIVE     = 'active';
    case LOSING     = 'losing';
    case LOST       = 'lost';
}
