<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 14:46:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail;

use App\Enums\EnumHelperTrait;

enum DispatchedEmailEventTypeEnum: string
{
    use EnumHelperTrait;

    case DELIVERY = 'delivery';
    case BOUNCE   = 'bounce';

    case COMPLAIN       = 'complain';
    case OPEN           = 'open';
    case CLICK          = 'click';
    case DELIVERY_DELAY = 'delivery-delay';

    case UNSUBSCRIBE          = 'unsubscribe';

}
