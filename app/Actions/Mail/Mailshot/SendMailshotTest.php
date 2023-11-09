<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\Ses\SendSesEmail;
use App\Enums\Mail\MailshotStateEnum;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Email;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Spatie\Mjml\Mjml;

class SendMailshotTest
{
    use AsCommand;
    use AsAction;

    public function handle(Mailshot $mailshot, array $modelData): Mailshot
    {
        $layout        = $mailshot->layout;
        $emailHtmlBody = Mjml::new()->minify()->toHtml($layout['html'][0]['html']);

        foreach (explode(',', $modelData['email']) as $email) {
            $email           = Email::firstOrCreate(['address' => $email]);
            $dispatchedEmail = DispatchedEmail::create(
                [
                    'email_id' => $email->id,
                    'date'     => now()
                ]
            );

            $dispatchedEmail->refresh();

            SendSesEmail::run($mailshot->subject, $emailHtmlBody, $dispatchedEmail, config('mail.devel.sender_email_address'));
        }

        return $mailshot;
    }

    public function htmlResponse(Mailshot $mailshot): RedirectResponse
    {

        /** @var Shop $scope */
        $scope = $mailshot->scope;

        return redirect()->route(
            'org.crm.shop.prospects.mailshots.show',
            array_merge(
                [
                    $scope->slug,
                    $mailshot->slug
                ],
                [
                    '_query' => [
                        'tab' => 'showcase'
                    ]
                ]
            )
        );
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function asController(Mailshot $mailshot, ActionRequest $request): Mailshot
    {
        if ($mailshot->state == MailshotStateEnum::IN_PROCESS) {
            $mailshot = SetMailshotAsReady::make()->action($mailshot, [
                'publisher_id' => $request->user()->id,
            ]);
        }
        $request->validate();

        return $this->handle($mailshot, $request->validated());
    }

}
