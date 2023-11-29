<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 22:31:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail\SenderEmail;

use App\Enums\EnumHelperTrait;

enum SenderEmailStateEnum: string
{
    use EnumHelperTrait;

    case VERIFICATION_NOT_SUBMITTED               = 'verification-not-submitted';
    case VERIFICATION_SUBMISSION_ERROR            = 'verification-submission-error';

    case PENDING                  = 'pending';
    case VERIFIED                 = 'verified';
    case FAIL                     = 'fail';


}
