<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Guest;

use App\Actions\Helpers\Uploads\ImportUpload;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Auth\GuestImport;
use App\Models\Auth\Guest;
use App\Models\Helpers\Upload;
use Lorisleiva\Actions\ActionRequest;

class ImportGuests
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, Guest::class);

        if ($this->isSync) {
            $upload = ImportUpload::run(
                $upload,
                new GuestImport($upload)
            );
        } else {
            ImportUpload::dispatch(
                $upload,
                new GuestImport($upload)
            );
        }

        return $upload;


    }

    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public string $commandSignature = 'guest:import {--g|g_drive} {filename}';
}
