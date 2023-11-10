<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Hydrators;

use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Events\MailshotPusherEvent;
use App\Models\Mail\Mailshot;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MailshotHydrateReadyEmails implements ShouldBeUnique
{
    use AsAction;
    use InteractsWithSockets;
    use SerializesModels;

    public int $jobUniqueFor = 3600;

    public function handle(Mailshot $mailshot): void
    {

        $count = DB::table('dispatched_emails')
            ->where('mailshot_id', $mailshot->id)->count();

        $countReady = DB::table('dispatched_emails')
            ->where('mailshot_id', $mailshot->id)
            ->where('dispatched_emails.state', DispatchedEmailStateEnum::READY->value)->count();



        $mailshot->mailshotStats()->update(
            [
                'number_dispatched_emails_state'      => $count,
                'number_dispatched_emails_state_ready'=> $countReady
            ]
        );

        MailshotPusherEvent::dispatch($mailshot);
    }


    public function getJobUniqueId(Mailshot $parameters): string
    {
        return $parameters->id;
    }
}
