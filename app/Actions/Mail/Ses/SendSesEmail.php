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
use Lorisleiva\Actions\Concerns\AsAction;

class SendSesEmail
{
    use AsAction;
    use AwsClient;

    public mixed $message;

    public function handle(string $subject, string $emailHtmlBody, EmailDelivery $emailDelivery, string $sender)
    {

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


        $emailData=[
            'Source'      => $sender,
            'Destination' => [
                'ToAddresses' => [$emailDelivery->email->address]
            ],
            'Message' => $message['Message']
        ];

        print_r($emailData);

        $result= $this->getSesClient()->sendEmail($emailData);

        dd($result);
        exit;

        $emailDelivery->update(
            [
                'state'  => EmailDeliveryStateEnum::SENT,
                'sent_at'=> now()
            ]
        );

    }


}
