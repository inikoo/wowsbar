<?php

namespace App\Imports\CRM;

use App\Actions\CRM\Prospect\StoreProspect;
use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Models\CRM\Prospect;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProspectImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public ExcelUpload $prospectUpload;
    public function __construct(ExcelUpload $prospectUpload)
    {
        $this->prospectUpload = $prospectUpload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        UpdateExcelUploads::run($this->prospectUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $prospect) {
            try {
                $prospect = ExcelUploadRecord::create([
                    'excel_upload_id' => $this->prospectUpload->id,
                    'data'            => json_encode(Arr::except($prospect, 'code'))
                ]);

                StoreProspect::run(json_decode($prospect->data, true));
                ImportExcelUploads::dispatch($prospect, count($collection), $totalImported++, Prospect::class);
            } catch (\Exception $e) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'contact_name'    => ['required', 'nullable', 'string', 'max:255'],
            'company_name'    => ['required', 'nullable', 'string', 'max:255'],
            'email'           => ['required', 'nullable', 'email'],
            'phone'           => ['required', 'nullable'],
            'contact_website' => ['required', 'nullable'],
        ];
    }
}
