<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Invoice;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateInvoices;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Accounting\Invoice;

class DeleteInvoice
{
    use WithActionUpdate;

    public function handle(
        Invoice $invoice,
        array $modelData
    ): Invoice {
        $invoice->invoiceTransactions()->delete();
        $invoice->delete();
        CustomerHydrateInvoices::dispatch($invoice->customer);

        return $this->update($invoice, $modelData, ['data']);
    }
}
