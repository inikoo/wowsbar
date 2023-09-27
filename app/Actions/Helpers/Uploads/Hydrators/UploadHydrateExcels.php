<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads\Hydrators;

use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Models\Media\ExcelUpload;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadHydrateExcels implements ShouldBeUnique
{
    use AsAction;

    public function handle(ExcelUpload $excelUpload): void
    {
        $stats = [
            'number_rows' => $excelUpload->records()->count(),
            'number_success' => $excelUpload->records()->where('status', UploadRecordStatusEnum::COMPLETE)->count(),
            'number_fails' => $excelUpload->records()->where('status', UploadRecordStatusEnum::FAILED)->count(),
        ];

        $excelUpload->update($stats);
    }

    public function getJobUniqueId(ExcelUpload $parameters): string
    {
        return $parameters->id;
    }
}
