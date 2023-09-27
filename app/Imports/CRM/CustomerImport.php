<?php

namespace App\Imports\CRM;

use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\Helpers\ExcelUpload\ExcelUploadRecord\UpdateImportExcelUploadStatus;
use App\Models\CRM\Customer;
use App\Models\Helpers\Upload;
use App\Models\Helpers\UploadRecord;
use App\Models\Market\Shop;
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

    public Upload $customerUpload;
    public function __construct(Upload $customerUpload)
    {
        $this->customerUpload = $customerUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        foreach ($collection as $value) {
            try {
                $customer = UploadRecord::create([
                    'excel_upload_id' => $this->customerUpload->id,
                    'data'            => json_encode([
                        'contact_name'    => $value['name'],
                        'email'           => $value['email'],
                        'contact_website' => $value['website']
                    ])
                ]);

                $shop = Shop::where('slug', Arr::get($value, 'shop'))->first();

                StoreCustomer::run($shop, Arr::except(json_decode($customer->data, true), 'shop'));
                UpdateImportExcelUploadStatus::run($customer, count($collection), $totalImported++, Customer::class);
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
