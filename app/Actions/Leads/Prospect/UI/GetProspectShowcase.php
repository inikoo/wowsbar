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
            'timeline'=> []
        ];

    }

}
