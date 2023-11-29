<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 28 Nov 2023 00:20:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Helpers\Query\GetQueryEloquentQueryBuilder;
use App\Actions\Helpers\Query\WithQueryCompiler;
use App\Actions\Traits\WithCheckCanContactByEmail;
use App\Enums\Mail\MailshotTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use App\Models\Mail\Mailshot;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsObject;

class GetRecipients
{
    use AsObject;
    use WithCheckCanContactByEmail;
    use WithQueryCompiler;

    /**
     * @throws \Exception
     */
    public function handle(Mailshot $mailshot)
    {
        return match (Arr::get($mailshot->recipients_recipe, 'recipient_builder_type')) {
            'query'                  => $this->getRecipientsQuery($mailshot),
            'prospects'              => $this->getEstimatedNumberRecipientsProspects(Arr::get($mailshot->recipients_recipe, 'recipient_builder_data.prospects')),
            'custom_prospects_query' => $this->getEstimatedNumberRecipientsCustomProspectsQuery($mailshot),
            default                  => 11
        };
    }

    private function getRecipientsQuery(Mailshot $mailshot)
    {
        $query        = Query::find(Arr::get($mailshot->recipients_recipe, 'recipient_builder_data.query.id'));

        return GetQueryEloquentQueryBuilder::run($query);
    }

    private function getEstimatedNumberRecipientsProspects($prospectIDs): int
    {
        $counter = 0;

        foreach ($prospectIDs as $prospectID) {
            $prospect = Prospect::find($prospectID);
            if (!$this->canContactByEmail($prospect)) {
                continue;
            }
            $counter++;
        }

        return $counter;
    }

    /**
     * @throws \Exception
     */
    private function getEstimatedNumberRecipientsCustomProspectsQuery(Mailshot $mailshot): \Spatie\QueryBuilder\QueryBuilder
    {
        $modelClass=match ($mailshot->type) {
            MailshotTypeEnum::PROSPECT_MAILSHOT=> Prospect::class,
            default                            => Customer::class
        };

        $compiledQueryData = $this->compileConstrains(Arr::get($mailshot->recipients_recipe, 'recipient_builder_data.custom_prospects_query'));
        return   GetQueryEloquentQueryBuilder::make()->buildQuery($modelClass, $mailshot->parent, $compiledQueryData);

    }

}
