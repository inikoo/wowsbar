<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Models\Market\ProductShop;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncProductTradeUnits
{
    use AsAction;

    public function handle(ProductShop $product, array $tradeUnitsData): ProductShop
    {
        $product->tradeUnits()->sync($tradeUnitsData);

        //SyncProductTradeUnitImages::run($product);


        return $product;
    }
}
