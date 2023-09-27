<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads;

use App\Models\Helpers\Upload;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;

class ImportModel
{
    use AsAction;
    use WithAttributes;

    public function handle($callback, Upload $excelUpload): Upload
    {
        Excel::import($callback, storage_path('app/' . $excelUpload->getFullPath()));


        return $excelUpload;
    }
}
