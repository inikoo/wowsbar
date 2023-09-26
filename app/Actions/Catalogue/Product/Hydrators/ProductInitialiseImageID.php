<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\Hydrators;

use App\Models\Market\ProductShop;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Fill image_id if id null and products has images (to be run after trade units set up or first image added)
 */
class ProductInitialiseImageID implements ShouldBeUnique
{
    use AsAction;

    public function handle(ProductShop $product): void
    {
        if ($product->images()->count()) {
            if ($product->image_id) {
                return;
            }

            $image = $product->images()->first();

            if ($image) {
                $product->update(
                    [
                        'image_id' => $image->id
                    ]
                );
            }
        }
    }

    public function getJobUniqueId(ProductShop $product): string
    {
        return $product->id;
    }
}
