<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\HydrateModel;
use App\Models\Market\ShopProduct;
use Illuminate\Support\Collection;

class HydrateProducts extends HydrateModel
{
    public string $commandSignature = 'hydrate:products {slugs?*}';


    public function handle(ShopProduct $product): void
    {
    }


    protected function getModel(string $slug): ShopProduct
    {
        return ShopProduct::firstWhere('slug', $slug);
    }

    protected function getAllModels(): Collection
    {
        return ShopProduct::withTrashed()->get();
    }
}
