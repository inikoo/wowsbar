<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Sep 2023 00:20:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Models\Helpers\Upload;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;

trait WithImportModel
{
    use AsAction;
    use WithAttributes;

    public function init(Upload $upload, $import): Upload
    {

        Excel::import(
            $import,
            storage_path('app/'.$upload->getFullPath())
        );

        $upload->refresh();
        return $upload;

    }

    public function asCommand(Command $command): void
    {
        $filename = $command->argument('filename');
        $file     = ConvertUploadedFile::run($filename);

        $upload = $this->handle($file);

        $command->table(
            ['Success', 'Fail'],
            [
                [
                    $upload->number_success,
                    $upload->number_fails
                ]
            ]
        );

        if ($upload->number_fails) {
            $failData = [];
            foreach ($upload->records()->where('status', UploadRecordStatusEnum::FAILED)->get() as $fail) {
                $failData[] = [$fail->row_number, implode($fail->errors)];
            }
            $command->table(
                ['Row', 'Error'],
                $failData
            );
        }
    }
}
