<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 04 Apr 2023 11:42:49 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Market\Product\Hydrators;

use App\Models\Market\Product;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Fill image_id if id null and products has images (to be run after trade units set up or first image added)
 */
class ProductInitialiseImageID implements ShouldBeUnique
{
    use AsAction;

    public function handle(Product $product): void
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

    public function getJobUniqueId(Product $product): string
    {
        return $product->id;
    }
}
