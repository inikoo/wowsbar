<?php

namespace App\Imports\HumanResources;

use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Models\HumanResources\Employee;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    public ExcelUpload $employeeUpload;

    public function __construct(ExcelUpload $employeeUpload)
    {
        $this->employeeUpload = $employeeUpload;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): void
    {
        $totalImported = 1;

        UpdateExcelUploads::run($this->employeeUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $employee) {
            try {
                $email    = $employee['workplace'] == 'bb' ? Str::lower($employee['nick_name']) . '@aw-advantage.com' : $employee['email'];
                $employee = ExcelUploadRecord::create([
                    'excel_upload_id' => $this->employeeUpload->id,
                    'data'            => json_encode([
                        'contact_name' => $employee['nick_name'],
                        'email'        => $email,
                        'workplace'    => $employee['workplace'],
                        'job_position' => $employee['position_code']
                    ])
                ]);

                StoreEmployee::run(json_decode($employee->data, true));
                ImportExcelUploads::dispatch($employee, count($collection), $totalImported++, Employee::class);
            } catch (\Exception $e) {
                $totalImported--;
            }
        }
    }

    public function rules(): array
    {
        return [
            'nick_name'     => ['required', 'max:255'],
            'position_code' => ['required', 'exists:job_positions,slug'],
            'workplace'     => ['required', ' string', 'exists:workplaces,slug'],
            'email'         => ['sometimes', 'required', 'email']
        ];
    }
}
