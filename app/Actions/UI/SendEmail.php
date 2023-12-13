<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Dec 2023 22:22:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Actions\Mail\Ses\SendSesEmail;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;

class SendEmail
{
    use AsAction;

    public function handle(string $subject, string $sender, string $recipient, string $message): void
    {
        $sendEmail = SendSesEmail::make();
        $sendEmail->sendEmail($sendEmail->getEmailData($subject, $sender, $recipient, $message));
    }

    public string $commandSignature = 'send:email';

    public function asCommand(Command $command): int
    {
        $recipient = $command->ask('Recipient Email');
        $subject   = $command->ask('Subject');
        $message   = $command->ask('Message');
        $sender = organisation()->shops->first()->senderEmail->email_address ?? env('SENDER_EMAIL_ADDRESS');

        $this->handle($subject, $sender, $recipient, $message);

        return 0;
    }

}
