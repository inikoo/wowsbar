<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\AwsEmail;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Mail\SenderEmail\SenderEmailStateEnum;
use App\Models\Mail\SenderEmail;
use Lorisleiva\Actions\ActionRequest;

class SendIdentityEmailVerification
{
    use WithActionUpdate;
    use AwsClient;

    private bool $asAction = false;

    public function handle(SenderEmail $senderEmail): SenderEmail
    {

        $email = $senderEmail->email_address;


        $state = CheckSenderEmailVerification::run($email);

        if (in_array($state, [SenderEmailStateEnum::VERIFIED, SenderEmailStateEnum::PENDING])) {
            if($senderEmail->verified_at === null) {
                data_set($modelData, 'verified_at', now());
            }

            data_set($modelData, 'state', $state);
            return $this->update($senderEmail, $modelData);
        }

        $result = $this->getSesClient()->verifyEmailIdentity([
            'EmailAddress' => $email,
        ]);

        if ($result['@metadata']['statusCode'] != 200) {
            $state = SenderEmailStateEnum::VERIFICATION_SUBMISSION_ERROR;
        } else {
            $state = SenderEmailStateEnum::PENDING;
            data_set($modelData, 'last_verification_submitted_at', now());
        }


        data_set($modelData, 'state', $state);

        return $this->update($senderEmail, $modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.edit");
    }


    public function jsonResponse(SenderEmail $senderEmail): array
    {
        return [
            'state'   => $senderEmail->state,
            'message' => match ($senderEmail->state) {
                SenderEmailStateEnum::FAIL                          => __('Verification mail expired, please try to verify again.'),
                SenderEmailStateEnum::VERIFICATION_NOT_SUBMITTED    => __('The email is not submitted for verification.'),
                SenderEmailStateEnum::VERIFICATION_SUBMISSION_ERROR => __('There was an error sending the verification email.'),
                SenderEmailStateEnum::VERIFIED                      => __('The email is validated 🎉.'),
                SenderEmailStateEnum::PENDING                       => __('We\'ve sent you verification to your email, please check your email.'),
            }
        ];
    }

    public function asController(SenderEmail $senderEmail, ActionRequest $request): SenderEmail
    {
        $this->validateAttributes();

        return $this->handle($senderEmail);
    }
}
