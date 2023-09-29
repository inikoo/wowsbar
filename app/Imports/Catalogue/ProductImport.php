<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 10:31:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\Catalogue;

use App\Actions\Catalogue\Product\StoreProduct;
use App\Enums\Catalogue\Product\ProductTypeEnum;
use App\Imports\WithImport;
use App\Models\Catalogue\ProductCategory;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use WithImport;


    public function storeModel($row, $uploadRecord): void
    {
        $departmentSlug = $row->get('department');
        if ($departmentSlug) {
            $parent = ProductCategory::where('slug', $departmentSlug)->first();
        } else {
            $parent = organisation();
        }


        $fields =
            array_merge(
                Arr::except(
                    array_keys($this->rules()),
                    ['department']
                ),
                []
            );


        try {
            StoreProduct::make()->action(
                $parent,
                $row->only($fields)->all()
            );
            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }


    public function rules(): array
    {
        return [
            'department' => ['nullable', 'exists:product_categories,code'],
            'code'       => ['required', 'iunique:products', 'between:2,9', 'alpha_dash'],
            'unit'       => ['required', 'string'],
            'price'      => ['required', 'numeric'],
            'name'       => ['required', 'max:250', 'string'],
            'type'       => ['required', Rule::in(ProductTypeEnum::values())],

        ];
    }
}
