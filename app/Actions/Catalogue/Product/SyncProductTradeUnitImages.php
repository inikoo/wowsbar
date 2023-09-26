<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Catalogue\Product\Hydrators\ProductInitialiseImageID;
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
