<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Helpers\Uploads\ImportUpload;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Catalogue\ProductImport;
use App\Models\Catalogue\Product;
use App\Models\Helpers\Upload;

class ImportProducts
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, Product::class);

        if ($this->isSync) {
            ImportUpload::run(
                $file,
                new ProductImport($upload)
            );
            $upload->refresh();
        } else {
            ImportUpload::dispatch(
                $this->tmpPath.$upload->filename,
                new ProductImport($upload)
            );
        }

        return $upload;
    }


    public string $commandSignature = 'product:import {--g|g_drive} {filename}';


}
