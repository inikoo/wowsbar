<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 16:16:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Hydrators;

use App\Enums\Mail\EmailDeliveryStateEnum;
use App\Events\SendEmailDetailToPusherEvent;
use App\Models\Mail\Mailshot;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MailshotHydrateSentEmails implements ShouldBeUnique
{
    use AsAction;
    use InteractsWithSockets;
    use SerializesModels;

    public int $jobUniqueFor = 3600;

    public function handle(Mailshot $mailshot): void
    {

        $count = DB::table('mailshot_recipients')
            ->leftJoin('email_deliveries', 'email_delivery_id', 'email_deliveries.id')
            ->where('mailshot_id', $mailshot->id)
            ->where('email_deliveries.state', EmailDeliveryStateEnum::SENT->value)->count();



        $mailshot->mailshotStats()->update(
            [
                'number_email_deliveries_state_sent'=> $count
            ]
        );

        SendEmailDetailToPusherEvent::dispatch($mailshot->mailshotStats);
    }


    public function getJobUniqueId(Mailshot $parameters): string
    {
        return $parameters->id;
    }
}
