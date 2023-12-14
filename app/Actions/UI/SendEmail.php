<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Dec 2023 02:29:15 Malaysia Time, Kuala Lumpur, Malaysia
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

    public string $commandSignature = 'send:email {email}' ;

    public function asCommand(Command $command): int
    {

        $subject   = $command->ask('Subject');
        $message   = $command->ask('Message');
        $sender    = organisation()->shops->first()->senderEmail->email_address ?? env('SENDER_EMAIL_ADDRESS');

        $this->handle($subject, $sender, $command->argument('email'), $message);

        return 0;
    }

}
