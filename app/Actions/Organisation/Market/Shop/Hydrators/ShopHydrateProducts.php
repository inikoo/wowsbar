<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 11 Mar 2023 16:06:25 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Market\Shop\Hydrators;

use App\Models\Organisation\Market\Product;
use App\Models\Organisation\Market\Shop;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateProducts implements ShouldBeUnique
{
    use AsAction;


    public function handle(Shop $shop): void
    {
        $stateCounts   = Product::where('shop_id', $shop->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();
        $stats         = [
            'number_products' => $shop->products->count(),
        ];
        foreach (ProductStateEnum::cases() as $productState) {
            $stats['number_products_state_'.$productState->snake()] = Arr::get($stateCounts, $productState->value, 0);
        }
        $shop->stats->update($stats);
    }

    public function getJobUniqueId(Shop $shop): string
    {
        return $shop->id;
    }
}