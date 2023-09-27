<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\StoreExcelUploads;
use App\Imports\Catalogue\ProductImport;
use App\Models\Catalogue\Product;
use Excel;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportProduct
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction          = false;
    public string $commandSignature = 'product:import {filename}';

    public function handle($file): void
    {
        $upload = StoreExcelUploads::run($file, Product::class);

        Excel::import(new ProductImport($upload), $upload->getFullPath());
    }

    /**
     * @throws \Throwable
     */
    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public function asCommand(Command $command): void
    {
        $filename = $command->argument('filename');
        $file     = ConvertUploadedFile::run($filename);

        $this->handle($file);
    }
}
