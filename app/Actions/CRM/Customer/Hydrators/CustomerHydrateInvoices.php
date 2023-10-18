<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Actions\Traits\WithElasticsearch;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Models\CRM\Customer;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateInvoices
{
    use AsAction;
    use WithElasticsearch;

    public function handle(Customer $customer): void
    {
        $numberInvoices = $customer->invoices->count();
        $stats          = [
            'number_invoices' => $numberInvoices,
        ];

        $customer->trade_state = match ($numberInvoices) {
            0       => CustomerTradeStateEnum::NONE,
            1       => CustomerTradeStateEnum::ONE,
            default => CustomerTradeStateEnum::MANY
        };
        $customer->save();

        $invoiceTypeCounts = Invoice::where('customer_id', $customer->id)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')->all();


        foreach (InvoiceTypeEnum::cases() as $invoiceType) {
            $stats['number_invoices_type_'.$invoiceType->snake()] = Arr::get($invoiceTypeCounts, $invoiceType->value, 0);
        }

        //        $this->storeElastic('invoice');

        $customer->stats->update($stats);
    }

    public function getJobUniqueId(Customer $customer): int
    {
        return $customer->id;
    }
}
