<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 11 Mar 2023 01:05:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Ses;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Models\Mail\DispatchedEmail;
use Aws\Exception\AwsException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class SendSesEmail
{
    use AsAction;
    use AwsClient;

    public mixed $message;

    public function handle(string $subject, string $emailHtmlBody, DispatchedEmail $dispatchedEmail, string $sender): DispatchedEmail
    {
        if ($dispatchedEmail->state != DispatchedEmailStateEnum::READY) {
            return $dispatchedEmail;
        }

        if (!app()->isProduction() and !config('mail.devel.send_ses_emails')) {
            $dispatchedEmail->update(
                [
                    'state'               => DispatchedEmailStateEnum::SENT,
                    'sent_at'             => now(),
                    'date'                => now(),
                    'provider_message_id' => 'devel-'.Str::uuid()
                ]
            );

            return $dispatchedEmail;
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
                'ToAddresses' => [
                    $dispatchedEmail->email->address
                ]
            ],
            'Message'     => $message['Message']
        ];


        $numberAttempts = 12;
        $attempt        = 0;

        do {
            try {
                $result = $this->getSesClient()->sendEmail($emailData);

                $dispatchedEmail->update(
                    [
                        'state'               => DispatchedEmailStateEnum::SENT,
                        'sent_at'             => now(),
                        'date'                => now(),
                        'provider_message_id' => Arr::get($result, 'MessageId')
                    ]
                );
            } catch (AwsException $e) {
                if ($e->getAwsErrorCode() == 'Throttling' and $attempt < $numberAttempts - 1) {
                    $attempt++;
                    usleep(rand(200, 300) + pow(2, $attempt));
                    continue;
                } else {
                    $dispatchedEmail->update(
                        [
                            'state'       => DispatchedEmailStateEnum::ERROR,
                            'date'        => now(),
                            'data->error' =>
                                [
                                    'code'    => $e->getAwsErrorCode(),
                                    'msg'     => $e->getAwsErrorMessage(),
                                    'attempt' => $attempt

                                ],
                        ]
                    );
                    break;
                }
            }

            break;
        } while ($attempt < $numberAttempts);




        return $dispatchedEmail;
    }


}
