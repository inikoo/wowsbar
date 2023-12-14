<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Dec 2023 02:29:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Actions\Mail\DispatchedEmail\StoreDispatchedEmail;
use App\Actions\Mail\Ses\SendSesEmail;
use App\Enums\Mail\Outbox\OutboxTypeEnum;
use App\Models\Mail\Email;
use App\Models\Mail\Outbox;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;

class SendEmail
{
    use AsAction;

    public function handle(string $subject, string $sender, string $recipient, string $message): void
    {
        $email           = Email::firstOrCreate(['address' => $recipient]);
        $dispatchedEmail = StoreDispatchedEmail::run($email, null, [
            'is_test'   => true,
            'outbox_id' => Outbox::where('type', OutboxTypeEnum::TEST)->pluck('id')->first()
        ]);

        SendSesEmail::run($subject, $message, $dispatchedEmail, $sender);
    }

    public string $commandSignature = 'send:email {email}' ;

    public function asCommand(Command $command): int
    {
        $senderEmail = organisation()->shops->first()->prospectsSenderEmail()->whereNotNull('email_address')->first();

        if (!$senderEmail) {
            $command->error('Sender email not set');
            return 1;
        }

        $senderEmailValidAt = organisation()->shops->first()->prospectsSenderEmail()->whereNotNull('verified_at')->first();

        if(!$senderEmailValidAt){
            $command->error('Sender email not verified');
            return 1;
        }

        $subject   = $command->ask('Subject');
        $message   = $command->ask('Message');
        $recipients = explode(',', $command->argument('email'));

        foreach ($recipients as $recipient) {
            $this->handle($subject, $senderEmail, $recipient, $message);
        }

        return 0;
    }
}
