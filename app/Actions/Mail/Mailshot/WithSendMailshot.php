<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Dec 2023 13:11:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\Ses\SendSesEmail;
use App\Models\Mail\DispatchedEmail;
use Illuminate\Support\Str;

trait WithSendMailshot
{
    public function sendEmailWithUnsubscribe(
        DispatchedEmail $dispatchedEmail,
        string $sender,
        string $subject,
        string $emailHtmlBody,
        string $unsubscribeUrl,
    ) {
        $html = $emailHtmlBody;

        if (preg_match_all("/{{(.*?)}}/", $html, $matches)) {
            foreach ($matches[1] as $i => $placeholder) {
                $placeholder = Str::kebab(trim($placeholder));
                if ($placeholder == 'unsubscribe') {
                    $placeholder = sprintf(
                        "<a ses:no-track href=\"$unsubscribeUrl\">%s</a>",
                        __('Unsubscribe')
                    );
                }

                $html = str_replace($matches[0][$i], sprintf('%s', $placeholder), $html);
            }
        }

        return SendSesEmail::run(
            subject:$subject,
            emailHtmlBody: $html,
            dispatchedEmail:$dispatchedEmail,
            sender:$sender,
            unsubscribeUrl:$unsubscribeUrl
        );
    }
}
