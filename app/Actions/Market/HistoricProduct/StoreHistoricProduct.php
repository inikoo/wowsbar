<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\HistoricProduct;

use App\Models\Market\HistoricProduct;
use App\Models\Market\Product;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreHistoricProduct
{
    use AsAction;

    public function handle(Product $product, array $modelData = []): HistoricProduct
    {
        $historicProductData = [
            'code'       => Arr::get($modelData, 'code', $product->code),
            'name'       => Arr::get($modelData, 'name', $product->name),
            'price'      => Arr::get($modelData, 'price', $product->price),
            'units'      => Arr::get($modelData, 'units', $product->units),
            'source_id'  => Arr::get($modelData, 'source_id'),


        ];
        if (Arr::get($modelData, 'created_at')) {
            $historicProductData['created_at'] = Arr::get($modelData, 'created_at');
        } else {
            $historicProductData['created_at'] = $product->created_at;
        }
        if (Arr::get($modelData, 'deleted_at')) {
            $historicProductData['deleted_at'] = Arr::get($modelData, 'deleted_at');
        }
        if (Arr::exists($modelData, 'status')) {
            $historicProductData['status'] = Arr::exists($modelData, 'status');
        } else {
            $historicProductData['status'] = true;
        }

        /** @var HistoricProduct $historicProduct */
        $historicProduct = $product->historicRecords()->create($historicProductData);
        $historicProduct->stats()->create();

        return $historicProduct;
    }
}
