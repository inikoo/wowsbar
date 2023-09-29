<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\HumanResources\EmployeeImport;
use App\Models\Helpers\Upload;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\ActionRequest;

class ImportEmployees
{
    use WithImportModel;

    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, Employee::class);

        return $this->init(
            $upload,
            new EmployeeImport($upload)
        );

    }

    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public string $commandSignature = 'employee:upload {--G|google=} {filename?}';


}
