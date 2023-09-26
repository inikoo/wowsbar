<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Market\Shop\Hydrators\ShopHydrateProducts;
use App\Actions\Catalogue\HistoricProduct\StoreHistoricProduct;
use App\Actions\Catalogue\Product\Hydrators\ProductHydrateUniversalSearch;
use App\Enums\Market\Product\ProductStateEnum;
use App\Enums\Market\Product\ProductTypeEnum;
use App\Models\Market\Product;
use App\Models\Market\ProductCategoryShop;
use App\Models\Market\Shop;
use App\Rules\CaseSensitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProduct
{
    use AsAction;
    use WithAttributes;

    private int $hydratorsDelay =0;

    public function handle(Shop|ProductCategoryShop $parent, array $modelData, bool $skipHistoric = false): Product
    {
        if(class_basename($parent)=='Shop') {
            $modelData['shop_id']    =$parent->id;
            $modelData['parent_id']  =$parent->id;
            $modelData['parent_type']= $parent->type;
            $modelData['owner_id']   = $parent->id;
            $modelData['owner_type'] = $parent->type;

        } else {
            $modelData['shop_id']    =$parent->shop_id;
            $modelData['owner_id']   = $parent->parent_id;
            $modelData['owner_type'] = $parent->shop->type;

        }
        /** @var Product $product */
        $product = $parent->products()->create($modelData);
        $product->stats()->create();
        if ($skipHistoric) {
            $historicProduct = StoreHistoricProduct::run($product);
            $product->update(
                [
                    'current_historic_product_id' => $historicProduct->id
                ]
            );
        }
        $product->salesStats()->create([
            'scope' => 'sales'
        ]);

        ShopHydrateProducts::dispatch($product->shop);
        ProductHydrateUniversalSearch::dispatch($product);
        return $product;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:products', 'between:2,9', 'alpha_dash', new CaseSensitive('products')],
            'units'       => ['sometimes', 'required', 'numeric'],
            'image_id'    => ['sometimes', 'required', 'exists:media,id'],
            'price'       => ['required', 'numeric'],
            'rrp'         => ['sometimes', 'required', 'numeric'],
            'name'        => ['required', 'max:250', 'string'],
            'state'       => ['sometimes', 'required', Rule::in(ProductStateEnum::values())],
            'type'        => ['required', Rule::in(ProductTypeEnum::values())],
            'description' => ['sometimes', 'required', 'max:1500']
        ];
    }

    public function action(Shop|Product $parent, array $objectData): Product
    {
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }

    public function inShop(Shop $shop, ActionRequest $request): RedirectResponse
    {
        $request->validate();
        $this->handle($shop, $request->all());

        return  Redirect::route('org.shops.show.products.index', $shop);
    }

    public function asFetch(Shop $shop, array $productData, int $hydratorsDelay=60): Product
    {
        $this->hydratorsDelay=$hydratorsDelay;
        return $this->handle($shop, $productData);
    }
}
