<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Catalogue\Product\Hydrators\ProductHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Market\ProductResource;
use App\Models\Market\ProductShop;
use Lorisleiva\Actions\ActionRequest;

class UpdateProduct
{
    use WithActionUpdate;

    private bool $asAction=false;

    public function handle(ProductShop $product, array $modelData, bool $skipHistoric=false): ProductShop
    {
        $product= $this->update($product, $modelData, ['data', 'settings']);

        ProductHydrateUniversalSearch::dispatch($product);

        return $product;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.products.edit");
    }
    public function rules(): array
    {
        return [
            'code'        => ['sometimes','required', 'unique:tenant.products', 'between:2,9', 'alpha_dash'],
            'units'       => ['sometimes', 'required', 'numeric'],
            'price'       => ['sometimes', 'required', 'numeric'],
            'name'        => ['sometimes','required', 'max:250', 'string'],
            'description' => ['sometimes', 'required', 'max:1500'],
        ];
    }

    public function asController(ProductShop $product, ActionRequest $request): ProductShop
    {
        $request->validate();
        return $this->handle($product, $request->all());
    }

    public function action(ProductShop $product, array $objectData): ProductShop
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($product, $validatedData);
    }

    public function jsonResponse(ProductShop $product): ProductResource
    {
        return new ProductResource($product);
    }
}
