<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Invoice\Hydrators;

use App\Models\Accounting\Invoice;
use Lorisleiva\Actions\Concerns\AsAction;

class InvoiceHydrateUniversalSearch
{
    use AsAction;


    public function handle(Invoice $invoice): void
    {
        $invoice->universalSearch()->updateOrCreate(
            [],
            [
                'in_organisation' => true,
                'section'         => 'accounting',
                'title'           => $invoice->number,
            ]
        );
    }

}
