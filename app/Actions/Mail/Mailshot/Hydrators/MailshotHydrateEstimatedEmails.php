<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 18:44:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Hydrators;

use App\Actions\Helpers\Query\GetQueryEloquentQueryBuilder;
use App\Actions\Traits\WithCheckCanSendEmail;
use App\Events\MailshotPusherEvent;
use App\Models\Helpers\Query;
use App\Models\Mail\Mailshot;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class MailshotHydrateEstimatedEmails
{
    use AsAction;
    use WithCheckCanSendEmail;



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


        $estimatedNumberRecipients =$this->getNumberEstimatedRecipients($mailshot->recipients_recipe);


        $mailshot->mailshotStats()->update(
            [
                'number_estimated_dispatched_emails'       => $estimatedNumberRecipients,
                'estimated_dispatched_emails_calculated_at'=> now()
            ]
        );

        if(config('mail.broadcast_dispatch_emails_stats')) {
            MailshotPusherEvent::dispatch($mailshot);
        }
    }

    public function getNumberEstimatedRecipients(array $recipientsRecipe): int
    {
        $query = Query::find(Arr::get($recipientsRecipe, 'query_id'));

        $queryBuilder = GetQueryEloquentQueryBuilder::run($query);

        $counter = 0;
        $queryBuilder->chunk(
            1000,
            function ($recipients) use (&$counter) {
                foreach ($recipients as $recipient) {
                    if (!$this->canSend($recipient)) {
                        continue;
                    }
                    $counter++;
                }


            }
        );

        return $counter;

    }


}
