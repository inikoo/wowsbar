<?php

namespace App\Actions\Mail\Mailshot;

use App\Enums\Mail\MailshotStateEnum;
use App\Models\Leads\Prospect;
use App\Models\Mail\Mailshot;
use App\Models\Mail\MailshotRecipient;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class StoreMailshotRecipients
{
    use AsAction;

    public function handle(Mailshot $mailshot): void
    {
        // TODO: FOR TESTING SEND EMAIL ONLY
        foreach (Prospect::all() as $prospect) {
            $mailshot->recipients()->updateOrCreate([
                'recipient_id' => $prospect->id,
                'recipient_type' => Prospect::class,
            ]);
        }
        // TODO: FOR TESTING SEND EMAIL ONLY
    }
}
