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
use App\Models\Market\Shop;
use Aws\Ses\Exception\SesException;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class ReSendIdentityEmailVerification
{
    use WithActionUpdate;
    use AwsClient;

    private bool $asAction = false;

    public function handle(SenderEmail $senderEmail): SenderEmail
    {
        $email = $senderEmail->email_address;

        try {
            $result = $this->getSesClient()->verifyEmailIdentity([
                'EmailAddress' => $email,
            ]);

            if ($result['@metadata']['statusCode'] != 200) {
                $state = SenderEmailStateEnum::VERIFICATION_SUBMISSION_ERROR;
            } else {
                $state = SenderEmailStateEnum::PENDING;
                data_set($modelData, 'last_verification_submitted_at', now());
            }
        } catch (SesException|Throwable) {
            $state = SenderEmailStateEnum::ERROR;
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
            'state'                            => $senderEmail->state,
            'message'                          => $senderEmail->state->message()[$senderEmail->state->value],
            'last_verification_submitted_at'   => $senderEmail->last_verification_submitted_at,
        ];
    }

    public function inShop(Shop $shop, ActionRequest $request): SenderEmail
    {
        $senderEmail = SenderEmail::whereEmailAddress($request->input('email'))->firstOrFail();

        return $this->handle($senderEmail);
    }

    public function asController(SenderEmail $senderEmail, ActionRequest $request): SenderEmail
    {
        return $this->handle($senderEmail);
    }
}
