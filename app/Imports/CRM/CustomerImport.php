<?php

namespace App\Imports\CRM;

use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\CRM\Prospect\StoreProspect;
use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Models\CRM\Customer;
use App\Models\CRM\Prospect;
use App\Models\Market\Shop;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public ExcelUpload $customerUpload;
    public function __construct(ExcelUpload $customerUpload)
    {
        $this->customerUpload = $customerUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        UpdateExcelUploads::run($this->customerUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $value) {
            try {
                $customer = ExcelUploadRecord::create([
                    'excel_upload_id' => $this->customerUpload->id,
                    'data'            => json_encode($value)
                ]);

                $shop = Shop::where('slug', Arr::get($value, 'shop'))->first();

                StoreCustomer::run($shop, Arr::except(json_decode($customer->data, true), 'shop'));
                ImportExcelUploads::dispatch($customer, count($collection), $totalImported++, Customer::class);
            } catch (\Exception $e) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'contact_name'             => ['nullable', 'string', 'max:255'],
            'company_name'             => ['nullable', 'string', 'max:255'],
            'email'                    => ['nullable', 'email'],
            'phone'                    => ['nullable'],
            'contact_website'          => ['nullable']
        ];
    }
}
