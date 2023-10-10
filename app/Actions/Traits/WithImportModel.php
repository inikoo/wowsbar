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
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
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
            storage_path('app/' . $upload->getFullPath())
        );

        $upload->refresh();
        return $upload;

    }

    public function rumImport($file, $command): Upload
    {
        return $this->handle($file);

    }

    public function asCommand(Command $command): int
    {
        $filename = $command->argument('filename');
        $newFileName = now()->timestamp . ".xlsx";

        if($command->option('g_drive')){
            $googleDisk = Storage::disk('google');

            if(!$googleDisk->exists($filename)){
                $command->error("$filename do not found in GDrive");
                return 1;
            }

            $content = $googleDisk->get($filename);

            Storage::disk('local')->put("tmp/$newFileName", $content);
        }



      //  if (!$filePath) {
        //    $filename = $this->downloadFromGoogle($command->option('google'));
        //    $filePath = "storage/app/tmp/" . $filename;
      //  }

        $file = ConvertUploadedFile::run("storage/app/tmp/" . $newFileName);

        $upload = $this->rumImport($file, $command);

        Storage::disk('local')->delete("tmp/" . $newFileName);

        $command->table(
            ['', 'Success', 'Fail'],
            [
                [
                    $command->getName(),
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

        return 0;
    }

    public function downloadFromGoogle(string $url): Exception|string
    {

        $googleDisk = Storage::disk('google');
        dd($googleDisk);

        /*
        try {
            $client = new Client();
            $client->setClientId('');
            $client->setClientSecret('');
            $client->refreshToken('');

            $client->addScope([
                Drive::DRIVE,
                Drive::DRIVE_FILE,
                Drive::DRIVE_METADATA,
                Drive::DRIVE_METADATA_READONLY,
                Drive::DRIVE_APPDATA,
                Sheets::DRIVE_FILE,
                Sheets::SPREADSHEETS,
                Sheets::DRIVE_READONLY,
                Sheets::DRIVE,
                Sheets::SPREADSHEETS_READONLY,
                Drive::DRIVE_READONLY
            ]);
            $client->setAccessType('offline');
            $driveService = new Drive($client);

            $filename = now()->timestamp;
            $fileId   = explode('/', $url)[5];

            $this->downloadType($driveService, $fileId, $filename);
        } catch (Exception $e) {
            dd($e->getMessage());
        }

        return "$filename.xlsx";
        */
    }

    public function downloadType($driveService, $fileId, $filename): void
    {
        try {
            $response = $driveService->files->export($fileId, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', array(
                'alt' => 'media'));

            Storage::disk('local')->put("tmp/$filename.xlsx", $response->getBody()->getContents());
        } catch (Exception $e) {
            if ($e->getCode() == 403) {
                $response = $driveService->files->get($fileId, array(
                    'alt' => 'media'));

                Storage::disk('local')->put("tmp/$filename.xlsx", $response->getBody()->getContents());
            }
        }
    }
}
