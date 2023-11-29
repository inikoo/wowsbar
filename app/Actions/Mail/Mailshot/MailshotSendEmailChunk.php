<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 15:02:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\Ses\SendSesEmail;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Mjml\Mjml;

class MailshotSendEmailChunk
{
    use AsAction;



    public function handle(Mailshot $mailshot, int $channel): void
    {


        $layout        = $mailshot->layout;
        $emailHtmlBody = Mjml::new()->minify()->toHtml($layout['html'][0]['html']);

        if(app()->environment('production')) {
            $sender=$mailshot->parent->sender_email_address;
        } else {
            $sender=config('mail.devel.sender_email_address');

        }

        $mailshot->update(
            [
                "channels->{$channel}->sending"=> now()
            ]
        );


        foreach ($mailshot->recipients()->where('channel', $channel)->get() as $recipient) {


            SendSesEmail::run(
                subject: $mailshot->subject,
                emailHtmlBody:$emailHtmlBody,
                emailDelivery: $recipient->emailDelivery,
                sender: $sender
            );
        }




        $mailshot->update(
            [
                "channels->{$channel}->sent"=> now()
            ]
        );

        $mailshot->refresh();

    }
}
