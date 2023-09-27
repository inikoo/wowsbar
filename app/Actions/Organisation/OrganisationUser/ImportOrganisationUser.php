<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser;

use App\Actions\Helpers\ExcelUpload\Hydrators\UploadHydrateStats;
use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\ImportModel;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Imports\Auth\OrganisationUserImport;
use App\Models\Auth\OrganisationUser;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportOrganisationUser
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction          = false;
    public string $commandSignature = 'organisation-user:import {filename}';

    public function handle($file): void
    {
        $upload      = StoreUploads::run($file, OrganisationUser::class);
        $excelUpload = ImportModel::run(new OrganisationUserImport($upload), $upload);

        UploadHydrateStats::dispatch($excelUpload);
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
