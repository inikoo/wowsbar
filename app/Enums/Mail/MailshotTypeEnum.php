<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 22:31:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail;

use App\Enums\EnumHelperTrait;

enum MailshotTypeEnum: string
{
    use EnumHelperTrait;

    case PROSPECT_MAILSHOT          = 'prospect_mailshot';
    case NEWSLETTER                 = 'newsletter';
    case CUSTOMER_PROSPECT_MAILSHOT = 'customer_prospect_mailshot';
    case MARKETING                  = 'marketing';
    case ANNOUNCEMENT               = 'announcement';


}
