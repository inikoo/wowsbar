<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\EmailAddress\SendEmailAddress;
use App\Enums\Mail\MailshotStateEnum;
use App\Models\Mail\Mailshot;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class SendMailshot
{
    use AsCommand;
    use AsAction;

    public function handle(Mailshot $mailshot, array $modelData): void
    {
        $updateData = array_merge([
            'state' => MailshotStateEnum::READY,
        ], $modelData);

        if(!$mailshot->sent_at) {
            data_set($modelData, 'sent_at', now());
        }
        data_set($modelData, 'state', MailshotStateEnum::SENDING);

        $mailshot->update($updateData);

        StoreMailshotRecipients::run($mailshot);
        SendEmailAddress::run($mailshot);
    }

    //    public function htmlResponse(Mailshot): RedirectResponse
    //    {
    //        return redirect()->route('org.models.mailshot.content.show');
    //    }

    public function asController(Mailshot $mailshot, ActionRequest $request): void
    {

        if($mailshot->state==MailshotStateEnum::IN_PROCESS) {
            $mailshot=SetMailshotAsReady::make()->action($mailshot, [
                'publisher_id' => $request->user()->id,
            ]);
        }

        $request->validate();
        $this->handle($mailshot, $request->validated());
    }

}
