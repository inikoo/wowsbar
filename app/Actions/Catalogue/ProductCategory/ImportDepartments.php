<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Sep 2023 10:26:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\ProductCategory;

use App\Actions\Helpers\Uploads\ImportUpload;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Catalogue\DepartmentImport;
use App\Models\Catalogue\ProductCategory;
use App\Models\Helpers\Upload;

class ImportDepartments
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, ProductCategory::class);

        if ($this->isSync) {
            ImportUpload::run(
                $file,
                new DepartmentImport($upload)
            );
            $upload->refresh();
        } else {
            ImportUpload::dispatch(
                $this->tmpPath.$upload->filename,
                new DepartmentImport($upload)
            );
        }

        return $upload;

    }

    public string $commandSignature = 'department:import {--g|g_drive} {filename}';


}
