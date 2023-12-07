<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 17:18:44 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Subscriptions\CustomerWebsite;

use App\Actions\Helpers\Uploads\ImportUpload;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Portfolios\CustomerWebsiteImport;
use App\Models\Helpers\Upload;
use App\Models\Portfolios\CustomerWebsite;
use Lorisleiva\Actions\ActionRequest;

class ImportCustomerWebsites
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, CustomerWebsite::class);

        if ($this->isSync) {
            $upload = ImportUpload::run(
                $upload,
                new CustomerWebsiteImport($upload)
            );
        } else {
            ImportUpload::dispatch(
                $upload,
                new CustomerWebsiteImport($upload)
            );
        }

        return $upload;


    }


    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public string $commandSignature = 'customer-website:import {--g|g_drive} {filename}';

}
