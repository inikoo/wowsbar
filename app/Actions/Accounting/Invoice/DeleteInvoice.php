<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
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
