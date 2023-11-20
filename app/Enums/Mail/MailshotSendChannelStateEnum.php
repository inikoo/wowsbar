<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 13 Nov 2023 15:45:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail;

use App\Enums\EnumHelperTrait;

enum MailshotSendChannelStateEnum: string
{
    use EnumHelperTrait;

    case READY      = 'ready';
    case SENDING    = 'sending';
    case SENT       = 'sent';
    case STOPPED    = 'stopped';

}
