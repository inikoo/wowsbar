<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Prospect;

use App\Actions\Traits\WithExportData;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Events\UploadExcelProgressEvent;
use App\Models\Media\ExcelUploadRecord;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportProspects
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(ExcelUploadRecord $prospectUploadRecord, $totalUploads, $totalImported): void
    {
        try {
            StoreProspect::run(json_decode($prospectUploadRecord->data, true));

            event(new UploadExcelProgressEvent(null, [
                'total_uploads'  => $totalUploads,
                'total_complete' => $totalImported
            ], 'ProspectUpload'));
            $prospectUploadRecord->update(['status' => UploadRecordStatusEnum::COMPLETE]);
        } catch (\Exception $e) {
            $prospectUploadRecord->update(['status' => UploadRecordStatusEnum::FAILED]);
        }
    }
}
