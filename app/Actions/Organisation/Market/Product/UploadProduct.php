<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Market\Product;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\StoreExcelUploads;
use App\Imports\Market\ProductImport;
use App\Models\Market\Product;
use Excel;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadProduct
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
