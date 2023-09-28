<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Catalogue\Product\Hydrators\ProductHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Catalogue\ProductResource;
use App\Models\Catalogue\Product;
use Lorisleiva\Actions\ActionRequest;

class UpdateProduct
{
    use WithActionUpdate;

    private bool $asAction=false;

    public function handle(Product $product, array $modelData, bool $skipHistoric=false): Product
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
            'code'        => ['sometimes','required', 'iunique:products', 'between:2,9', 'alpha_dash'],
            'units'       => ['sometimes', 'required', 'numeric'],
            'price'       => ['sometimes', 'required', 'numeric'],
            'name'        => ['sometimes','required', 'max:250', 'string'],
            'description' => ['sometimes', 'required', 'max:1500'],
        ];
    }

    public function asController(Product $product, ActionRequest $request): Product
    {
        $request->validate();
        return $this->handle($product, $request->all());
    }

    public function action(Product $product, array $objectData): Product
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($product, $validatedData);
    }

    public function jsonResponse(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
