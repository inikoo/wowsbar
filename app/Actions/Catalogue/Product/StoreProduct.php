<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Catalogue\Product\Hydrators\ProductHydrateUniversalSearch;

use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateProducts;
use App\Enums\Catalogue\Product\ProductStateEnum;
use App\Enums\Catalogue\Product\ProductTypeEnum;
use App\Models\Catalogue\Product;
use App\Models\Catalogue\ProductCategory;
use App\Models\SysAdmin\Organisation;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProduct
{
    use AsAction;
    use WithAttributes;


    public function handle(Organisation|ProductCategory $parent, array $modelData): Product
    {
        /** @var Product $product */
        $product = $parent->products()->create($modelData);
        $product->stats()->create();

        $product->salesStats()->create([
            'scope' => 'sales'
        ]);

        //        OrganisationHydrateProducts::dispatch();
        ProductHydrateUniversalSearch::dispatch($product);

        return $product;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'iunique:products', 'between:2,9', 'alpha_dash'],
            'unit'        => ['required', 'string'],
            'price'       => ['required', 'numeric'],
            'name'        => ['required', 'max:250', 'string'],
            'type'        => ['required', Rule::in(ProductTypeEnum::values())],
            'state'       => ['sometimes', 'required', Rule::in(ProductStateEnum::values())],
            'description' => ['sometimes', 'required', 'max:1500']
        ];
    }

    public function action(Organisation|ProductCategory $parent, array $objectData): Product
    {
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }


}
