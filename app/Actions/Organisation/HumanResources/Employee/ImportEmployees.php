<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources\Employee;

use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadExcelProgressEvent;
use App\Models\HumanResources\Employee;
use App\Models\Media\ExcelUploadRecord;
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
    public function handle(ExcelUploadRecord $employeeUploadRecord, $totalUploads, $totalImported): void
    {
        try {
            StoreEmployee::run(json_decode($employeeUploadRecord->data, true));

            event(new UploadExcelProgressEvent([
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ], Employee::class));
            $employeeUploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $employeeUploadRecord->update(['status' => UploadRecordStatusEnum::FAILED]);
        }
    }
}
