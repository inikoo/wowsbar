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
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Email;
use App\Models\Mail\Outbox;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;

class SendEmail
{
    use AsAction;

    public function handle(string $subject, string $sender, string $recipient, string $message, $showUnsubscribe): DispatchedEmail
    {
        $email           = Email::firstOrCreate(['address' => $recipient]);
        $dispatchedEmail = StoreDispatchedEmail::run($email, null, [
            'is_test'   => true,
            'outbox_id' => Outbox::where('type', OutboxTypeEnum::TEST)->pluck('id')->first()
        ]);

        $unsubscribeUrl=null;
        if ($showUnsubscribe) {
            $unsubscribeUrl = route('org.unsubscribe.mailshot.show', $dispatchedEmail->ulid);
        }



        return SendSesEmail::run($subject, $message, $dispatchedEmail, $sender, $unsubscribeUrl);
    }

    public string $commandSignature = 'send:email {email}';

    public function asCommand(Command $command): int
    {
        $shop = organisation()->shops->first();

        $senderEmail = $shop->prospectsSenderEmail->email_address;

        if (!$senderEmail) {
            $command->error('Sender email not set');

            return 1;
        }

        $senderEmailValidAt = $shop->prospectsSenderEmail()->whereNotNull('verified_at')->first();

        if (!$senderEmailValidAt) {
            $command->error('Sender email not verified');

            return 1;
        }

        $subject    = $command->ask('Subject');
        $message    = $command->ask('Message');
        $recipients = explode(',', $command->argument('email'));


        foreach ($recipients as $recipient) {
            $dispatchedEmail = $this->handle($subject, $senderEmail, $recipient, $message, true);
            if ($dispatchedEmail->is_sent) {
                $command->info(sprintf('Email sent to %s', $recipient));
            } else {
                $command->error(sprintf('Email not sent to %s', $recipient));
                $command->error(Arr::get($dispatchedEmail->data, 'error.code').' - '.Arr::get($dispatchedEmail->data, 'error.msg'));
            }
        }

        return 0;
    }
}
