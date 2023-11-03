<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 11 Mar 2023 01:05:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Ses;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Models\Mail\MailshotRecipient;
use Aws\Result;
use Lorisleiva\Actions\Concerns\AsAction;

class SendSesEmail
{
    use AsAction;
    use AwsClient;

    public mixed $message;

    public function handle(MailshotRecipient $mailshotRecipient): Result
    {
        $mailshot = $mailshotRecipient->mailshot;
        $layout   = $mailshot->layout;
        $subject  = $mailshot->subject;

        $message = [
            'Message' => [
                'Subject' => [
                    'Data' => $subject,
                ]
            ]
        ];

        $message['Message']['Body']['Html'] = [
            'Data' => $layout['html'][0]['html']
        ];

        return $this->getSesClient()->sendEmail([
            'Source'      => $this->generateSenderEmail(),
            'Destination' => [
                'ToAddresses' => [$mailshotRecipient->recipient->email]
            ],
            'Message' => $message['Message']
        ]);
    }

    public function generateSenderEmail(): string
    {
        $user = request()->user();

        return ($user?->username ?? 'no-reply') . '@' . organisation()->code . env('MAIL_MAIN_URL');
    }
}
