<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 16:16:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Hydrators;

use App\Events\MailshotPusherEvent;
use App\Models\Mail\Mailshot;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MailshotHydrateSentEmails
{
    use AsAction;

    private Mailshot $mailshot;

    public function __construct(Mailshot $mailshot)
    {
        $this->mailshot = $mailshot;
    }

    public function getJobMiddleware(): array
    {
        return [(new WithoutOverlapping($this->mailshot->id))->dontRelease()];
    }

    public function handle(Mailshot $mailshot): void
    {

        $count = DB::table('dispatched_emails')
            ->where('mailshot_id', $mailshot->id)
            ->where('is_sent', true)->count();


        $mailshot->mailshotStats()->update(
            [
                'number_sent_emails'=> $count
            ]
        );

        if(config('mail.broadcast_dispatch_emails_stats')) {
            MailshotPusherEvent::dispatch($mailshot);
        }
    }


}
