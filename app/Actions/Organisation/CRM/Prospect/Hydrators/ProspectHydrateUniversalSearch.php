<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Prospect\Hydrators;

use App\Models\CRM\Prospect;
use Lorisleiva\Actions\Concerns\AsAction;

class ProspectHydrateUniversalSearch
{
    use AsAction;

    public function handle(Prospect $prospect): void
    {
        $prospect->universalSearch()->updateOrCreate(
            [],
            [
                'section'     => 'crm',
                'title'       => trim($prospect->name.' '.$prospect->contact_name),
                'description' => trim($prospect->email.' '.$prospect->company_name)
            ]
        );
    }

}
