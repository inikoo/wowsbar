<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Prospect\Hydrators;

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
