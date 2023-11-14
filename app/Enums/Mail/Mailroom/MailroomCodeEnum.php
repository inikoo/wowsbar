<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail\Mailroom;

use App\Enums\EnumHelperTrait;

enum MailroomCodeEnum: string
{
    use EnumHelperTrait;

    case MARKETING             = 'marketing';
    case LEADS                 = 'leads';
    case CUSTOMER_NOTIFICATION = 'customer-notification';
    case USER_NOTIFICATION     = 'user-notification';

    public function label(): string
    {
        return match ($this) {
            MailroomCodeEnum::MARKETING             => 'Marketing',
            MailroomCodeEnum::LEADS                 => 'Leads',
            MailroomCodeEnum::CUSTOMER_NOTIFICATION => 'Customer notifications',
            MailroomCodeEnum::USER_NOTIFICATION     => 'User notifications',
        };
    }
}
