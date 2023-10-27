<?php

namespace App\Exports;

use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use App\Models\Helpers\Upload;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExcelFailsExport implements FromArray, ShouldAutoSize
{
    public Upload $upload;

    public function __construct(Upload $upload)
    {
        return $this->upload = $upload;
    }

    public function array(): array
    {
        $records = $this->upload->records()->where('status', UploadRecordStatusEnum::FAILED);

        $array = [
            array_keys($records->first()->values)
        ];

        foreach ($records->get() as $record) {
            $array[] = array_values($record->values);
        }

        return $array;
    }
}
