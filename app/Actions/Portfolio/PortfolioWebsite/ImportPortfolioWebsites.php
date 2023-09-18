<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadExcelProgressEvent;
use App\Models\Media\ExcelUploadRecord;
use App\Models\Tenancy\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportPortfolioWebsites
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(Tenant|null $tenant, ExcelUploadRecord $websiteUploadRecord, $totalUploads, $totalImported): void
    {
        try {
            StorePortfolioWebsite::run(json_decode($websiteUploadRecord->data, true));

            event(new UploadExcelProgressEvent($tenant, [
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ]));
            $websiteUploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $websiteUploadRecord->update(['status' => UploadRecordStatusEnum::FAILED]);
        }
    }
}