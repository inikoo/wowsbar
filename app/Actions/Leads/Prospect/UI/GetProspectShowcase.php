<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 14:28:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Http\Resources\CRM\ProspectResource;
use App\Models\Leads\Prospect;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProspectShowcase
{
    use AsObject;

    public function handle(Prospect $prospect): array
    {

        return [
            'info'    => ProspectResource::make($prospect)->getArray(),
            'timeline'=> [
                [
                    'title' => 'Prospect Created',
                    'created_at' => $prospect->created_at
                ],
                [
                    'title' => 'Prospect Updated',
                    'updated_at' => $prospect->updated_at
                ],
                [
                    'title' => 'Prospect Deleted',
                    'deleted_at' => $prospect->deleted_at
                ],
                [
                    'title' => 'Prospect Last Contacted',
                    'restored_at' => $prospect->last_contacted_at
                ],
                [
                    'title' => 'Prospect State',
                    'restored_at' => $prospect->state
                ],
                [
                    'title' => 'Prospect Not Interested',
                    'restored_at' => $prospect->not_interested_at
                ],
                [
                    'title' => 'Prospect Registered',
                    'restored_at' => $prospect->registered_at
                ],
                [
                    'title' => 'Prospect Invoiced',
                    'restored_at' => $prospect->invoiced_at
                ]
            ]
        ];

    }

}
