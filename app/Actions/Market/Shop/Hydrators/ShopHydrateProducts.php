<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Enums\Catalogue\Product\ProductStateEnum;
use App\Models\Market\ShopProduct;
use App\Models\Market\Shop;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateProducts implements ShouldBeUnique
{
    use AsAction;

    public function handle(Shop $shop): void
    {
        // todo: implement this
        /*
        $stateCounts   = ShopProduct::where('shop_id', $shop->id)
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
        */
    }

    public function getJobUniqueId(Shop $parameters): string
    {
        return $parameters->id;
    }
}
