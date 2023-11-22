<?php

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateEstimatedEmails;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class EstimateRecipientsCreatingMailshot
{
    use AsCommand;
    use AsAction;

    public function handle(): int
    {
        // TODO: What need to do here?

        $mailshot = Mailshot::find(1); // Just for placeholder

        return MailshotHydrateEstimatedEmails::make()->getNumberEstimatedRecipients($mailshot->recipients_recipe);
    }

    public function asController(): int
    {
        return $this->handle();
    }
}
