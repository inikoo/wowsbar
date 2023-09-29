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
use Google\Client;
use Google\Service\Drive;

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
        $filename = $command->option('filename');
        if($command->option('google')) {
            $filename = $this->downloadFromGoogle($command->option('google'));
        }

        $file     = ConvertUploadedFile::run($filename);

        $upload = $this->handle($file);

        $command->table(
            ['','Success', 'Fail'],
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
    }

    public function downloadFromGoogle(string $url)
    {
        try {
            $client = new Client();
            $client->useApplicationDefaultCredentials();
            $client->addScope(Drive::DRIVE);
            $driveService = new Drive($client);

            $fileId = explode('/', $url)[5];

            $response = $driveService->files->get($fileId, array(
                'alt' => 'media'));

            Storage::disk('local')->put("tmp/$fileId.xlsx", $response->getBody()->getContents());

            return "storage/app/tmp/$fileId.xlsx";
        } catch(Exception $e) {
            return $e;
        }
    }
}
