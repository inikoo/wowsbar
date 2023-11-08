<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 11 Mar 2023 01:05:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Ses;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Enums\Mail\EmailDeliveryStateEnum;
use App\Models\Mail\EmailDelivery;
use Aws\Exception\AwsException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class SendSesEmail
{
    use AsAction;
    use AwsClient;

    public mixed $message;

    public function handle(string $subject, string $emailHtmlBody, EmailDelivery $emailDelivery, string $sender): EmailDelivery
    {
        if ($emailDelivery->state != EmailDeliveryStateEnum::READY) {
            return $emailDelivery;
        }

        if(!app()->isProduction() and !config('mail.devel.send_ses_emails')) {
            $emailDelivery->update(
                [
                    'state'               => EmailDeliveryStateEnum::SENT,
                    'sent_at'             => now(),
                    'date'                => now(),
                    'provider_message_id' => 'devel-'.Str::uuid()
                ]
            );

            return $emailDelivery;
        }



        $message = [
            'Message' => [
                'Subject' => [
                    'Data' => $subject,
                ]
            ]
        ];

        $message['Message']['Body']['Html'] = [
            'Data' => $emailHtmlBody
        ];


        $emailData = [
            'Source'      => $sender,
            'Destination' => [
                'ToAddresses' => [$emailDelivery->email->address]
            ],
            'Message'     => $message['Message']
        ];


        try {
            $result = $this->getSesClient()->sendEmail($emailData);
            //   dd($result);


            $emailDelivery->update(
                [
                    'state'               => EmailDeliveryStateEnum::SENT,
                    'sent_at'             => now(),
                    'date'                => now(),
                    'provider_message_id' => Arr::get($result, 'MessageId')
                ]
            );
        } catch (AwsException $e) {
            $emailDelivery->update(
                [
                    'state'       => EmailDeliveryStateEnum::ERROR,
                    'date'        => now(),
                    'data->error' => $e->getAwsErrorMessage()
                ]
            );
        }

        return $emailDelivery;
    }


}
