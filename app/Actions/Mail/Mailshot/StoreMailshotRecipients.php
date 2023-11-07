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


        $query       =Query::find(Arr::get($mailshot->recipients_recipe, 'query_id'));
        $queryBuilder=BuildQuery::run($query);


        foreach($queryBuilder->get() as $item) {
            dd($item);
        }

        dd($query);

        // TODO: FOR TESTING SEND EMAIL ONLY
        foreach (Prospect::where('email', 'dev@aw-advantage.com')->get() as $prospect) {
            $mailshot->recipients()->updateOrCreate([
                'recipient_id'   => $prospect->id,
                'recipient_type' => Prospect::class,
            ]);
        }
        // TODO: FOR TESTING SEND EMAIL ONLY
    }
}
