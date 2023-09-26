<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 10:31:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\Catalogue;

use App\Actions\Catalogue\Product\StoreProduct;
use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Enums\Catalogue\Product\ProductStateEnum;
use App\Enums\Catalogue\Product\ProductTypeEnum;
use App\Models\Catalogue\Product;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use App\Rules\CaseSensitive;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToCollection, WithHeadingRow, WithValidation
{
//    use SkipsFailures;

    public ExcelUpload $productUpload;
    public function __construct(ExcelUpload $productUpload)
    {
        $this->productUpload = $productUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        UpdateExcelUploads::run($this->productUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $product) {
            try {
                $product = ExcelUploadRecord::create([
                        'excel_upload_id' => $this->productUpload->id,
                        'data'            => json_encode($product)
                ]);

                StoreProduct::run(organisation(), [
                    'code' => Arr::get($product->data, 'code'),
                    'name' => Arr::get($product->data, 'name'),
                    'price' => Arr::get($product->data, 'price'),
                    'units' => Arr::get($product->data, 'unit')
                ]);
                ImportExcelUploads::dispatch($product, count($collection), $totalImported++, Product::class);
            } catch (Exception) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'department'  => ['required', 'exists:product_categories,code'],
            'code'        => ['required', 'unique:products', 'between:2,9', 'alpha_dash'],
            'name'        => ['required', 'max:250', 'string'],
            'units'       => ['sometimes', 'required', 'string'],
            'price'       => ['required', 'numeric']
        ];
    }
}
