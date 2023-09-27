<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\StoreExcelUploads;
use App\Imports\CRM\CustomerImport;
use App\Models\CRM\Customer;
use Excel;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportCustomer
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction          = false;
    public string $commandSignature = 'customer:import {filename}';

    public function handle($file): void
    {
        $customerUpload = StoreExcelUploads::run($file, Customer::class);

        Excel::import(new CustomerImport($customerUpload), storage_path('app/' . $customerUpload->getFullPath()));
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
