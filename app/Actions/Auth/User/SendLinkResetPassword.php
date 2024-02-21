<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Mail\Mailshot\WithSendMailshot;
use App\Actions\Mail\Ses\SendSesEmail;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class SendLinkResetPassword
{
    use AsAction;
    use WithSendMailshot;

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function handle(string $token, string $email): void
    {
        $url = route('public.reset-password.edit', [
            'token' => $token,
            'email' => $email
        ]);
        $sender = config('mail.devel.sender_email_address');

        $emailHtmlBody = file_get_contents(base_path('database/seeders/datasets/reset-password-templates/reset_password.html'));

        $emailData = $this->getEmailData(
            'Reset Password Link',
            $sender,
            $email,
            $emailHtmlBody,
            $url
        );

        SendSesEmail::make()->sendEmail($emailData);
    }

    public function getEmailData(string $subject, string $sender, string $email, string $html, string $url): array
    {
        if (preg_match_all("/{{(.*?)}}/", $html, $matches)) {
            foreach ($matches[1] as $i => $placeholder) {
                $placeholder = $this->replaceMergeTags($placeholder, $url);
                $html        = str_replace($matches[0][$i], sprintf('%s', $placeholder), $html);
            }
        }

        if (preg_match_all("/\[(.*?)]/", $html, $matches)) {
            foreach ($matches[1] as $i => $placeholder) {
                $placeholder = $this->replaceMergeTags($placeholder, $url);
                $html        = str_replace($matches[0][$i], sprintf('%s', $placeholder), $html);
            }
        }

        return SendSesEmail::make()->getEmailData($subject, $sender, $email, $html);
    }

    private function replaceMergeTags($placeholder, $url): string
    {
        $placeholder = Str::kebab(trim($placeholder));

        return match ($placeholder) {
            'reset-password-url' => $url,
            default              => ''
        };
    }
}
