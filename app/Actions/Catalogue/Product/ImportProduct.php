<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\ImportModel;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Imports\Catalogue\ProductImport;
use App\Models\Catalogue\Product;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportProduct
{
    use AsAction;
    use WithAttributes;


    public string $commandSignature = 'product:import {filename}';

    public function handle($file)
    {
        $upload = StoreUploads::run($file, Product::class);

        return ImportModel::run(new ProductImport($upload), $upload);
    }


    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public function asCommand(Command $command): void
    {
        $filename = $command->argument('filename');
        $file     = ConvertUploadedFile::run($filename);

        $result=$this->handle($file);
    }
}
