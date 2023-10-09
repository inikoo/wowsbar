<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Actions\Traits\WithElasticsearch;
use App\Enums\Accounting\Invoice\InvoiceTypeEnum;
use App\Models\Accounting\Invoice;
use App\Models\Market\Shop;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateInvoices implements ShouldBeUnique
{
    use AsAction;

    use WithElasticsearch;

    public function handle(Shop $shop): void
    {

        // todo implement this
        /*
           $stats = [
               'number_invoices' => $shop->invoices->count(),

           ];

           $invoiceTypeCounts = Invoice::where('shop_id', $shop->id)
               ->selectRaw('type, count(*) as total')
               ->groupBy('type')
               ->pluck('total', 'type')->all();


           foreach (InvoiceTypeEnum::cases() as $invoiceType) {
               $stats['number_invoices_type_'.$invoiceType->snake()] = Arr::get($invoiceTypeCounts, $invoiceType->value, 0);
           }


           $shop->stats->update($stats);
         */
    }

    public function getJobUniqueId(Shop $shop): string
    {
        return $shop->id;
    }
}
