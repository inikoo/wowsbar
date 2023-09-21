<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads;

use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadExcelProgressEvent;
use App\Models\Media\ExcelUploadRecord;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportExcelUploads
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(ExcelUploadRecord $uploadRecord, $totalUploads, $totalImported, $type): void
    {
        try {
            event(new UploadExcelProgressEvent([
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ], class_basename($type)));
            $uploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $uploadRecord->update([
                'status' => UploadRecordStatusEnum::FAILED,
                'comment' => $e->getMessage()
            ]);
        }
    }
}
