<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Sep 2023 10:26:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\ProductCategory;

use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Catalogue\DepartmentImport;
use App\Models\Catalogue\ProductCategory;
use App\Models\Helpers\Upload;
use Lorisleiva\Actions\ActionRequest;

class ImportDepartments
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, ProductCategory::class);

        return $this->init(
            $upload,
            new DepartmentImport($upload)
        );
    }


    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public string $commandSignature = 'department:import {--g|g_drive} {filename}';


}
