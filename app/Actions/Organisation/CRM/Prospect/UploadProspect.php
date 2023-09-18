<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Prospect;

use App\Imports\CRM\ProspectImport;
use App\Models\CRM\Prospect;
use Excel;
use finfo;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadProspect
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction = false;
    public string $commandSignature = 'prospect:import {filename}';

    public function handle($file): void
    {
        $filename = $file->hashName();

        $path = 'org/prospects';
        Storage::disk('local')->put($path, $file);

        $prospectUpload = StoreProspectUploads::run([
            'type' => class_basename(Prospect::class),
            'original_filename' => $file->getClientOriginalName(),
            'filename'          => $filename
        ]);

        Excel::import(new ProspectImport($prospectUpload), $path . '/' . $filename);
    }

    /**
     * @throws \Throwable
     */
    public function asController(ActionRequest $request): void
    {
        $file     = $request->file('file');
        $this->handle($file);
    }

    public function asCommand(Command $command): void
    {
        $filename = $command->argument('filename');
        $path = 'org/prospects';
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $fullPath = storage_path('app/' . $path . '/' . $filename);

        if (Storage::disk('local')->exists('org/prospects/' . $filename)) {
            $file = new UploadedFile(
                $fullPath,
                $filename,
                $finfo->file($fullPath),
                filesize($fullPath),
                0,
                false
            );

            $this->handle($file);
        }
    }
}
