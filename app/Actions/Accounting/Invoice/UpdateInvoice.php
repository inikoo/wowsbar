<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Invoice;

use App\Actions\Accounting\Invoice\Hydrators\InvoiceHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Accounting\InvoiceResource;
use App\Models\Accounting\Invoice;
use Illuminate\Support\Arr;

class UpdateInvoice
{
    use WithActionUpdate;

    public function handle(Invoice $invoice, array $modelData): Invoice
    {
        $invoice->update(Arr::except($modelData, ['data']));
        $invoice->update($this->extractJson($modelData));

        InvoiceHydrateUniversalSearch::dispatch($invoice);

        return $this->update($invoice, $modelData, ['data']);
    }

    public function rules(): array
    {
        return [
            'number'      => ['sometimes', 'unique:invoices', 'numeric'],
            'currency_id' => ['sometimes', 'required', 'exists:currencies,id']
        ];
    }

    public function action(Invoice $invoice, array $modelData): Invoice
    {
        return $this->handle($invoice, $modelData);
    }

    public function jsonResponse(Invoice $invoice): InvoiceResource
    {
        return new InvoiceResource($invoice);
    }
}
