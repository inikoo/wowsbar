<?php

namespace App\Actions\Mail\Mailshot;

use App\Models\Leads\Prospect;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreMailshotRecipients
{
    use AsAction;

    public function handle(Mailshot $mailshot): void
    {
        // TODO: FOR TESTING SEND EMAIL ONLY
        foreach (Prospect::all() as $prospect) {
            $mailshot->recipients()->updateOrCreate([
                'recipient_id'   => $prospect->id,
                'recipient_type' => Prospect::class,
            ]);
        }
        // TODO: FOR TESTING SEND EMAIL ONLY
    }
}
