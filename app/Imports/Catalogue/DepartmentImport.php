<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 10:31:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\Catalogue;

use App\Actions\Catalogue\ProductCategory\StoreProductCategory;
use App\Imports\WithImport;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use WithImport;

    public function storeModel($row, $uploadRecord): void
    {

        $fields =$this->getFieldsFromRules();
        $parent = organisation();
        try {
            StoreProductCategory::make()->action(
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
            'code'       => ['required', 'iunique:product_categories', 'between:2,9', 'alpha_dash'],
            'name'       => ['required', 'max:250', 'string'],
        ];
    }
}
