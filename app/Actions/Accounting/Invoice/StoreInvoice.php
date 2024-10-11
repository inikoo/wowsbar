<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Invoice;

use App\Actions\Accounting\Invoice\Hydrators\InvoiceHydrateUniversalSearch;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateInvoices;
use App\Actions\Helpers\Address\AttachHistoricAddressToModel;
use App\Actions\Helpers\Address\StoreHistoricAddress;
use App\Actions\Market\Shop\Hydrators\ShopHydrateInvoices;
use App\Models\Accounting\Invoice;
use App\Models\CRM\Customer;
use App\Models\Helpers\Address;
use App\Models\OMS\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreInvoice
{
    use AsAction;
    public int $hydratorsDelay=0;

    public function handle(
        Customer|Order $parent,
        array $modelData,
        Address $billingAddress,
    ): Invoice {


        if (class_basename($parent)=='Customer') {
            $modelData['customer_id'] = $parent->id;
        } else {
            $modelData['customer_id'] = $parent->customer_id;

        }
        $modelData['shop_id']     = $parent->shop_id;
        $modelData['currency_id'] = $parent->shop->currency_id;




        /** @var \App\Models\Accounting\Invoice $invoice */
        $invoice = $parent->invoices()->create($modelData);
        $invoice->stats()->create();

        $billingAddress = StoreHistoricAddress::run($billingAddress);
        AttachHistoricAddressToModel::run($invoice, $billingAddress, ['scope' => 'billing']);

        CustomerHydrateInvoices::dispatch($invoice->customer)->delay($this->hydratorsDelay);
        ShopHydrateInvoices::dispatch($invoice->shop)->delay($this->hydratorsDelay);
        InvoiceHydrateUniversalSearch::dispatch($invoice);


        return $invoice;
    }

    public function asFetch(
        Customer|Order $parent,
        array $modelData,
        Address $billingAddress,
        int $hydratorsDelay = 60
    ): Invoice {
        $this->hydratorsDelay = $hydratorsDelay;

        return $this->handle($parent, $modelData, $billingAddress);
    }

    public function rules(): array
    {
        return [
            'number'      => ['required', 'unique:invoices', 'numeric'],
            'currency_id' => ['required', 'exists:currencies,id']
        ];
    }

    public function action(Customer|Order $parent, array $modelData, Address $billingAddress): Invoice
    {
        return $this->handle($parent, $modelData, $billingAddress);
    }
}
