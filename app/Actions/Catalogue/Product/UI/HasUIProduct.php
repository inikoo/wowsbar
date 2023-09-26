<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\UI;

use App\Actions\Market\Shop\UI\IndexShops;
use App\Models\Market\Product;

trait HasUIProduct
{
    public function getBreadcrumbs(Product $product): array
    {
        return array_merge(
            (new IndexShops())->getBreadcrumbs(),
            [
                'shops.show' => [
                    'route'           => 'shops.show',
                    'routeParameters' => $product->id,
                    'name'            => $product->code,
                    'index'           => [
                        'route'   => 'shops.index',
                        'overlay' => __('Products list')
                    ],
                    'modelLabel' => [
                        'label' => __('product')
                    ],
                ],
            ]
        );
    }
}
