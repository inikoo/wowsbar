<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:53:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\ProductCategory;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Catalogue\DepartmentResource;
use App\Models\Market\ProductCategoryShop;
use Lorisleiva\Actions\ActionRequest;

class UpdateProductCategory
{
    use WithActionUpdate;


    private bool $asAction=false;

    public function handle(ProductCategoryShop $productCategory, array $modelData): ProductCategoryShop
    {
        $productCategory = $this->update($productCategory, $modelData, ['data']);
        //ProductCategoryHydrateUniversalSearch::dispatch($productCategory);

        return $productCategory;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.department.edit");
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:product_categories', 'between:2,9', 'alpha'],
            'name'        => ['required', 'max:250', 'string'],
            'image_id'    => ['sometimes', 'required', 'exists:media,id'],
            'state'       => ['sometimes', 'required'],
            'description' => ['sometimes', 'required', 'max:1500'],
        ];
    }

    public function action(ProductCategoryShop $productCategory, array $objectData): ProductCategoryShop
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();
        return $this->handle($productCategory, $validatedData);
    }

    public function asController(ProductCategoryShop $productCategory, ActionRequest $request): ProductCategoryShop
    {
        $productCategory = $productCategory::where('slug', $request->route()->parameters)->first();
        return $this->handle($productCategory, $request->all());
    }

    public function jsonResponse(ProductCategoryShop $productCategory): DepartmentResource
    {
        return new DepartmentResource($productCategory);
    }
}
