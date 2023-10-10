<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Catalogue\ProductImport;
use App\Models\Catalogue\Product;
use App\Models\Helpers\Upload;
use Lorisleiva\Actions\ActionRequest;

class ImportProducts
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, Product::class);

        return $this->init(
            $upload,
            new ProductImport($upload)
        );
    }


    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public string $commandSignature = 'product:import {--g|g_drive} {filename}';


}
