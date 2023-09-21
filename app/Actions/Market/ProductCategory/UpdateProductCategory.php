<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\ProductCategory;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Market\DepartmentResource;
use App\Models\Market\ProductCategory;
use Lorisleiva\Actions\ActionRequest;

class UpdateProductCategory
{
    use WithActionUpdate;


    private bool $asAction=false;

    public function handle(ProductCategory $productCategory, array $modelData): ProductCategory
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

    public function action(ProductCategory $productCategory, array $objectData): ProductCategory
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();
        return $this->handle($productCategory, $validatedData);
    }

    public function asController(ProductCategory $productCategory, ActionRequest $request): ProductCategory
    {
        $productCategory = $productCategory::where('slug', $request->route()->parameters)->first();
        return $this->handle($productCategory, $request->all());
    }

    public function jsonResponse(ProductCategory $productCategory): DepartmentResource
    {
        return new DepartmentResource($productCategory);
    }
}
