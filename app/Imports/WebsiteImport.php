<?php

namespace App\Imports;

use App\Actions\Tenant\Portfolio\PortfolioWebsite\ImportPortfolioWebsites;
use App\Models\WebsiteUploadRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class WebsiteImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        foreach ($collection as $website) {
            $website = WebsiteUploadRecord::create([
                'tenant_id' => app('currentTenant')->id,
                'data'      => json_encode($website)
            ]);

            ImportPortfolioWebsites::dispatch(app('currentTenant'), $website, count($collection));
        }
    }
}
