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

    case VERIFICATION_NOT_SUBMITTED    = 'verification-not-submitted';
    case VERIFICATION_SUBMISSION_ERROR = 'verification-submission-error';

    case PENDING  = 'pending';
    case VERIFIED = 'verified';
    case FAIL     = 'fail';
    case ERROR    = 'error';


    public static function message(): array
    {
        return [
            'verification-not-submitted'    => __('The email is not submitted for verification.'),
            'verification-submission-error' => __('There was an error sending the verification email.'),
            'pending'                       => __('We\'ve sent you verification to your email, please check your email.'),
            'verified'                      => __('The email is validated 🎉.'),
            'fail'                          => __('Verification mail expired, please try to verify again.'),
            'error'                         => __('Verification process failed, please try again later')

        ];
    }

}
