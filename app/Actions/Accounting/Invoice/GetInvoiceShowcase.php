<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Invoice;

use App\Models\Accounting\Invoice;
use Lorisleiva\Actions\Concerns\AsObject;

class GetInvoiceShowcase
{
    use AsObject;

    public function handle(Invoice $invoice): array
    {
        return [
            []
        ];
    }
}
