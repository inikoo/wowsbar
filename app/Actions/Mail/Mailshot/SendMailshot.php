<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\EmailAddress\SendEmailAddress;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class SendMailshot
{
    use AsCommand;
    use AsAction;

    public function handle(Mailshot $mailshot): void
    {
        StoreMailshotRecipients::run($mailshot);

        SendEmailAddress::run($mailshot);
    }
}
