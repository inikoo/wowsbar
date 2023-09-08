<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite;

use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadWebsiteProgressEvent;
use App\Exports\PortfolioWebsite\BannersExport;
use App\Models\Tenancy\Tenant;
use App\Models\WebsiteUploadRecord;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportPortfolioWebsites
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(Tenant $tenant, WebsiteUploadRecord $websiteUploadRecord, $totalUploads, $totalImported): void
    {
        try {
//            StorePortfolioWebsite::run(json_decode($websiteUploadRecord->data, true));

            event(new UploadWebsiteProgressEvent($tenant, [
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ]));
            $websiteUploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $websiteUploadRecord->update(['status' => UploadRecordStatusEnum::FAILED]);
        }
    }
}
