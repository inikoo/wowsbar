<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 13 Nov 2023 17:02:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\MailshotSendChannel;

use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateDispatchedEmails;
use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateSentEmails;
use App\Actions\Mail\Mailshot\UpdateMailshotSentState;
use App\Actions\Mail\Ses\SendSesEmail;
use App\Enums\Mail\MailshotSendChannelStateEnum;
use App\Models\Mail\Mailshot;
use App\Models\Mail\MailshotSendChannel;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Mjml\Mjml;

class SendMailshotChannel
{
    use AsAction;

    public string $jobQueue = 'ses';

    public function handle(MailshotSendChannel $mailshotSendChannel): void
    {
        $mailshot      = $mailshotSendChannel->mailshot;
        $layout        = $mailshot->layout;
        $emailHtmlBody = Mjml::new()->minify()->toHtml($layout['html'][0]['html']);


        UpdateMailshotSendChannel::run(
            $mailshotSendChannel,
            [
                'start_sending_at' => now(),
                'state'            => MailshotSendChannelStateEnum::SENDING
            ]
        );


        foreach ($mailshot->recipients()->where('channel', $mailshotSendChannel->id)->get() as $recipient) {
            SendSesEmail::run(
                subject: $mailshot->subject,
                emailHtmlBody: $emailHtmlBody,
                dispatchedEmail: $recipient->dispatchedEmail,
                sender: $mailshot->sender()
            );
        }


        UpdateMailshotSendChannel::run(
            $mailshotSendChannel,
            [
                'sent_at' => now(),
                'state'   => MailshotSendChannelStateEnum::SENT
            ]
        );
        $mailshot->refresh();
        MailshotHydrateSentEmails::run($mailshot);
        MailshotHydrateDispatchedEmails::run($mailshot);
        UpdateMailshotSentState::run($mailshot);

    }

    public string $commandSignature = 'mailshot:send-channel {mailshot} {?channel}';


    public function asCommand(Command $command): int
    {
        try {
            $mailshot = Mailshot::where('slug', $command->argument('mailshot'))->firstOrFail();
        } catch (Exception) {
            $command->error('Mailshot not found');

            return 1;
        }

        $chanelQuery = $mailshot->channels();
        if ($command->argument('channel')) {
            $chanelQuery->where('channel.id', $command->argument('channel'));
        }

        foreach ($chanelQuery->get() as $channel) {
            $this->handle($channel);
        }


        return 0;
    }

}
