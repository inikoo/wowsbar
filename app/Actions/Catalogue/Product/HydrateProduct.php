<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\HydrateModel;
use App\Actions\Catalogue\Product\Hydrators\ProductInitialiseImageID;
use App\Models\Market\Product;
use Illuminate\Support\Collection;

class HydrateProduct extends HydrateModel
{
    public string $commandSignature = 'hydrate:product {tenants?*} {--i|id=} ';


    public function handle(Product $product): void
    {
        ProductInitialiseImageID::run($product);
    }


    protected function getModel(int $id): Product
    {
        return Product::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Product::withTrashed()->get();
    }
}
