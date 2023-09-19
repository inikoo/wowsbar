<?php

namespace App\Imports\Market;

use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Enums\Market\Product\ProductStateEnum;
use App\Enums\Market\Product\ProductTypeEnum;
use App\Models\Market\Product;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use App\Rules\CaseSensitive;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

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
                        'data' => json_encode($product)
                ]);

                ImportExcelUploads::dispatch($product, count($collection), $totalImported++, Product::class);
            } catch (\Exception $e) {
                $totalImported--;
            }
        }
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
}
