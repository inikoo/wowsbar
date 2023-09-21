<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\InvoiceTransaction;

use App\Models\Accounting\Invoice;
use App\Models\Accounting\InvoiceTransaction;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreInvoiceTransaction
{
    use AsAction;

    public function handle(Invoice $invoice, array $modelData): InvoiceTransaction
    {
        $modelData['shop_id']     = $invoice->shop_id;
        $modelData['customer_id'] = $invoice->customer_id;
        $modelData['order_id']    = $invoice->order_id;
        /** @var \App\Models\Accounting\InvoiceTransaction $invoiceTransaction */
        $invoiceTransaction = $invoice->invoiceTransactions()->create($modelData);

        return $invoiceTransaction;
    }
}
