<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 07 Dec 2023 13:30:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads;

use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;
use Maatwebsite\Excel\Facades\Excel;

class ImportUpload
{
    use AsAction;

    public function handle(UploadedFile $file, $import): void
    {

        Excel::import(
            $import,
            $file->path()
        );


    }

}
