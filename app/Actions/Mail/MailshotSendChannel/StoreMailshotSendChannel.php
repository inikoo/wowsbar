<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\MailshotSendChannel;

use App\Models\Mail\Mailshot;
use App\Models\Mail\MailshotSendChannel;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreMailshotSendChannel
{
    use AsAction;

    public function handle(Mailshot $mailshot, array $modelData = []): MailshotSendChannel
    {
        data_set($modelData, 'number_emails', 0, overwrite: false);
        /** @var MailshotSendChannel $mailshotSendChannel */
        $mailshotSendChannel = $mailshot->channels()->create($modelData);

        return $mailshotSendChannel;
    }


}
