<?php

namespace App\Imports;

use App\Actions\Helpers\ExcelUpload\ExcelUploadRecord\UpdateImportExcelUploadStatus;
use App\Actions\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Actions\Portfolio\Uploads\UpdatePortfolioWebsiteUploads;
use App\Models\CRM\Customer;
use App\Models\Helpers\Upload;
use App\Models\Helpers\UploadRecord;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WebsiteImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public Upload $websiteUpload;
    public Customer $customer;
    public function __construct(Upload $websiteUpload, Customer $customer)
    {
        $this->websiteUpload = $websiteUpload;
        $this->customer      = $customer;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        UpdatePortfolioWebsiteUploads::run($this->websiteUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $website) {
            try {
                $website = UploadRecord::create([
                    'excel_upload_id'   => $this->websiteUpload->id,
                    'data'              => json_encode([
                        'code'        => $website['code'],
                        'name'        => $website['name'],
                        'domain'      => $website['domain'],
                        'customer_id' => $this->customer->id
                    ])
                ]);

                StorePortfolioWebsite::run(json_decode($website->data, true));
                UpdateImportExcelUploadStatus::dispatch($website, count($collection), $totalImported++, PortfolioWebsite::class);
            } catch (\Exception $e) {
                $totalImported--;
            }

        }
    }

    public function rules(): array
    {
        return [
            'code'   => ['required', 'string'],
            'name'   => ['required', 'string'],
            'domain' => ['required', 'string']
        ];
    }
}
