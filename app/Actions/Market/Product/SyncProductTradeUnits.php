<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Product;

use App\Models\Market\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncProductTradeUnits
{
    use AsAction;

    public function handle(Product $product, array $tradeUnitsData): Product
    {
        $product->tradeUnits()->sync($tradeUnitsData);

        //SyncProductTradeUnitImages::run($product);


        return $product;
    }
}
