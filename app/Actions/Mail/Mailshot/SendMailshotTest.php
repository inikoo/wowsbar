<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\DispatchedEmail\StoreDispatchedEmail;
use App\Actions\Mail\Ses\SendSesEmail;
use App\Http\Resources\Mail\DispatchedEmailResource;
use App\Models\Mail\Email;
use App\Models\Mail\Mailshot;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Spatie\Mjml\Mjml;

class SendMailshotTest
{
    use AsCommand;
    use AsAction;

    public function handle(Mailshot $mailshot, array $modelData): Collection
    {
        $layout        = $mailshot->layout;
        $emailHtmlBody = Mjml::new()->minify()->toHtml($layout['html'][0]['html']);

        if (preg_match_all("/{{(.*?)}}/", $emailHtmlBody, $matches)) {
            foreach ($matches[1] as $i => $placeholder) {
                $placeholder = Str::kebab(Str::trim($placeholder));

                //  if($placeholder=='unsubscribe')

                //$emailHtmlBody = str_replace($matches[0][$i], sprintf('%s', $placeholder), $emailHtmlBody);
            }
        }

        //   dd($emailHtmlBody);

        $dispatchedEmails = [];
        foreach (Arr::get($modelData, 'emails', []) as $email) {
            $email           = Email::firstOrCreate(['address' => $email]);
            $dispatchedEmail = StoreDispatchedEmail::run($email, $mailshot, [
                'is_test' => true
            ]);
            $dispatchedEmail->refresh();
            $dispatchedEmails[] = SendSesEmail::run($mailshot->subject, $emailHtmlBody, $dispatchedEmail, $mailshot->sender());
        }

        return collect($dispatchedEmails);
    }

    public function jsonResponse($dispatchedEmails): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DispatchedEmailResource::collection($dispatchedEmails);
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('emails')) {
            $request->merge([
                'emails' =>
                    array_map('trim', explode(",", $request->get('emails')))
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'emails'   => ['required', 'array'],
            'emails.*' => 'required|email:rfc,dns'
        ];
    }

    public function asController(Mailshot $mailshot, ActionRequest $request): Collection
    {
        $request->validate();

        return $this->handle($mailshot, $request->validated());
    }

}
