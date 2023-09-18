<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources\Employee;

use App\Actions\Tenant\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadExcelProgressEvent;
use App\Models\Media\ExcelUploadRecord;
use App\Models\Tenancy\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportEmployees
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(ExcelUploadRecord $websiteUploadRecord, $totalUploads, $totalImported): void
    {
        try {
            StoreEmployee::run(json_decode($websiteUploadRecord->data, true));

            event(new UploadExcelProgressEvent(null, [
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ], 'EmployeeUpload'));
            $websiteUploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $websiteUploadRecord->update(['status' => UploadRecordStatusEnum::FAILED]);
        }
    }
}
