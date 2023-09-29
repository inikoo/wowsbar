<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 20:01:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\ExcelUpload\ExcelUploadRecord;

use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadExcelProgressEvent;
use App\Models\Helpers\UploadRecord;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateImportExcelUploadStatus
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(UploadRecord $uploadRecord, $totalUploads, $totalImported, $model): void
    {
        try {
            event(new UploadExcelProgressEvent([
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ], class_basename($model)));

            $uploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $uploadRecord->update([
                'status'  => UploadRecordStatusEnum::FAILED,
                'comment' => $e->getMessage()
            ]);
        }

    }
}
