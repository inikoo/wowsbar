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

        if ($mailshot->state == MailshotStateEnum::IN_PROCESS) {
            $updateData['sent_at'] = now();
        }

        $mailshot->update($updateData);

        StoreMailshotRecipients::run($mailshot);
        SendEmailAddress::run($mailshot);
    }

    public function asController(Mailshot $mailshot, ActionRequest $request): Mailshot
    {

        if($mailshot->state==MailshotStateEnum::IN_PROCESS) {
            $mailshot=SetMailshotAsReady::make()->action($mailshot, [
                'publisher_id' => $request->user()->id,
            ]);
        }

        $request->validate();
        return $this->handle($mailshot, $request->validated());
    }

}
