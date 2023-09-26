<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\Hydrators;

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
                'in_organisation' => true,
                'section'         => 'shops',
                'title'           => $product->name,
                'description'     => $product->code
            ]
        );
    }
}
