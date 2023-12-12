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

class ImportCustomerWebsites
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, CustomerWebsite::class);

        if ($this->isSync) {
            ImportUpload::run(
                $file,
                new CustomerWebsiteImport($upload)
            );
            $upload->refresh();
        } else {
            ImportUpload::dispatch(
                $this->tmpPath.$upload->filename,
                new CustomerWebsiteImport($upload)
            );
        }

        return $upload;

    }


    public string $commandSignature = 'customer-website:import {--g|g_drive} {filename}';

}
