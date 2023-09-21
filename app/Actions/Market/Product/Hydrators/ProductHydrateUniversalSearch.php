<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Product\Hydrators;

use App\Models\Market\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class ProductHydrateUniversalSearch
{
    use AsAction;

    public function handle(Product $product): void
    {
        $product->universalSearch()->updateOrCreate(
            [],
            [
                'section'     => 'shops',
                'title'       => $product->name,
                'description' => $product->code
            ]
        );
    }
}
