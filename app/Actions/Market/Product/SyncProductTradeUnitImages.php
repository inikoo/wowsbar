<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Product;

use App\Actions\Market\Product\Hydrators\ProductInitialiseImageID;
use App\Models\Market\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncProductTradeUnitImages
{
    use AsAction;

    public function handle(Product $product): Product
    {
        $images = [];


        foreach ($product->tradeUnits as $tradeUnit) {
            foreach ($tradeUnit->media as $media) {
                $images[$media->id] = [
                    'owner_type' => 'TradeUnit',
                    'owner_id'   => $tradeUnit->id,
                    'type'       => 'image'
                ];
            }
        }
        /*
         * To this to work delete TradeUnit images should delete this record as well
         */

        $product->images()->syncWithoutDetaching($images);

        ProductInitialiseImageID::run($product);


        return $product;
    }
}
