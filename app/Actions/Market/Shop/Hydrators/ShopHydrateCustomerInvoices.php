<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateCustomerInvoices
{
    use AsAction;


    public function handle(Shop $shop): void
    {
        $stats = [];

        $numberInvoicesStateCounts = Customer::where('shop_id', $shop->id)
            ->selectRaw('trade_state, count(*) as total')
            ->groupBy('trade_state')
            ->pluck('total', 'trade_state')->all();

        //todo implement this if needed
        /*
        foreach (CustomerTradeStateEnum::cases() as $tradeState) {
            $stats['number_customers_trade_state_'.$tradeState->snake()] =
                Arr::get($numberInvoicesStateCounts, $tradeState->value, 0);
        }
        */
        $shop->stats->update($stats);
    }

    public function getJobUniqueId(Shop $shop): string
    {
        return $shop->id;
    }
}
