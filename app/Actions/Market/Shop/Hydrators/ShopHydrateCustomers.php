<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateCustomers
{
    use AsAction;


    public function handle(Shop $shop): void
    {
        $stats = [
            'number_customers' => $shop->customers->count(),
        ];

        $stateCounts = Customer::where('shop_id', $shop->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();


        foreach (CustomerStateEnum::cases() as $customerState) {
            $stats['number_customers_state_'.$customerState->snake()] =
                Arr::get($stateCounts, $customerState->value, 0);
        }


        $shop->crmStats()->update($stats);
    }


}
