<?php

namespace App\Imports;

use App\Actions\Tenant\Portfolio\PortfolioWebsite\ImportPortfolioWebsites;
use App\Actions\Tenant\Portfolio\Uploads\UpdatePortfolioWebsiteUploads;
use App\Models\WebsiteUpload;
use App\Models\WebsiteUploadRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WebsiteImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public WebsiteUpload $websiteUpload;
    public function __construct(WebsiteUpload $websiteUpload)
    {
        $this->websiteUpload = $websiteUpload;
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
                $website = WebsiteUploadRecord::create([
                    'tenant_id'         => app('currentTenant')->id,
                    'website_upload_id' => $this->websiteUpload->id,
                    'data'              => json_encode([
                        'code'   => $website['code'],
                        'name'   => $website['name'],
                        'domain' => $website['domain']
                    ])
                ]);

                ImportPortfolioWebsites::dispatch(app('currentTenant'), $website, count($collection), $totalImported++);
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
