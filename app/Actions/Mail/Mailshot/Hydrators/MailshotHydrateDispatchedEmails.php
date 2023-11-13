<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Events\MailshotPusherEvent;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Mailshot;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MailshotHydrateDispatchedEmails implements ShouldBeUnique
{
    use AsAction;
    use InteractsWithSockets;
    use SerializesModels;
    use WithEnumStats;

    public int $jobUniqueFor = 3600;

    public function handle(Mailshot $mailshot): void
    {
        $stats = $this->getEnumStats(
            model: 'dispatched_emails',
            field: 'state',
            enum: DispatchedEmailStateEnum::class,
            models: DispatchedEmail::class,
            where: function ($q) use ($mailshot) {
                $q->where('mailshot_id', $mailshot->id);
            }
        );
        $mailshot->mailshotStats()->update($stats);

        MailshotPusherEvent::dispatch($mailshot);
    }


    public function getJobUniqueId(Mailshot $parameters): string
    {
        return $parameters->id;
    }
}
