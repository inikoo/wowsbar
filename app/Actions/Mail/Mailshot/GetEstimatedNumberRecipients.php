<?php

namespace App\Actions\Mail\Mailshot;

use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\Concerns\AsCommand;

class GetEstimatedNumberRecipients
{
    use AsCommand;

    public function handle(Mailshot $mailshot): int
    {
        return $mailshot->recipients()->count();
    }
}
