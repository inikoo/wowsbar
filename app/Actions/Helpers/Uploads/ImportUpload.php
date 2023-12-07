<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 07 Dec 2023 13:30:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads;

use App\Models\Helpers\Upload;
use Lorisleiva\Actions\Concerns\AsAction;
use Maatwebsite\Excel\Facades\Excel;

class ImportUpload
{
    use AsAction;

    public function handle(Upload $upload, $import): Upload
    {

        Excel::import(
            $import,
            storage_path('app/' . $upload->getFullPath())
        );

        $upload->refresh();
        return $upload;

    }

}
