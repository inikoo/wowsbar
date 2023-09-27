<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Imports\HumanResources\EmployeeImport;
use App\Models\Helpers\Upload;
use App\Models\HumanResources\Employee;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployees
{
    use AsAction;
    use WithAttributes;


    public function handle($file): Upload
    {
        $upload = StoreUploads::run($file, Employee::class);

        Excel::import(
            new EmployeeImport($upload),
            storage_path('app/'.$upload->getFullPath())
        );

        $upload->refresh();

        return $upload;
    }


    public function asController(ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($file);
    }

    public string $commandSignature = 'employee:upload {filename}';

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
