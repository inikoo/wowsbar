<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 18:44:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Hydrators;

use App\Actions\Helpers\Query\BuildQuery;
use App\Actions\Traits\WithCheckCanSendEmail;
use App\Events\MailshotPusherEvent;
use App\Models\Helpers\Query;
use App\Models\Mail\Mailshot;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class MailshotHydrateEstimatedEmails
{
    use AsAction;
    use InteractsWithSockets;
    use SerializesModels;
    use WithCheckCanSendEmail;


    /**
     * @var \App\Models\Mail\Mailshot
     */
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


        $query = Query::find(Arr::get($mailshot->recipients_recipe, 'query_id'));

        $queryBuilder = BuildQuery::run($query);

        $counter = 0;
        $queryBuilder->chunk(
            1000,
            function ($recipients) use ($mailshot, &$counter) {
                foreach ($recipients as $recipient) {
                    if (!$this->canSend($recipient)) {
                        continue;
                    }
                    $counter++;
                }


            }
        );



        $mailshot->mailshotStats()->update(
            [
                'number_estimated_dispatched_emails'       => $counter,
                'estimated_dispatched_emails_calculated_at'=> now()
            ]
        );

        MailshotPusherEvent::dispatch($mailshot);
    }



}
