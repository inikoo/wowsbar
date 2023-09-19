<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads;

use App\Models\Media\ExcelUpload;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateExcelUploads
{
    use AsAction;
    use WithAttributes;

    public function handle(ExcelUpload $excelUpload, array $data): ExcelUpload
    {
        $excelUpload->update($data);

        return $excelUpload;
    }
}
