<?php

namespace App\Actions\Mail\Mailshot;

use App\Enums\Mail\MailshotStateEnum;
use App\Models\Mail\Mailshot;
use App\Models\Mail\MailshotRecipient;
use Lorisleiva\Actions\Concerns\AsCommand;

class GetEstimatedNumberRecipients
{
    use AsCommand;

    public function handle(Mailshot $mailshot): int
    {
        return $mailshot->recipients()->count();
    }
}
