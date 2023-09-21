<?php

namespace App\Imports\HumanResources;

use App\Actions\Helpers\Uploads\ImportExcelUploads;
use App\Actions\Helpers\Uploads\UpdateExcelUploads;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Models\HumanResources\Employee;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
                $employee = ExcelUploadRecord::create([
                    'excel_upload_id' => $this->employeeUpload->id,
                    'data'            => json_encode(Arr::except($employee, 'code'))
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
            'contact_name'      => ['required', 'max:255'],
            'date_of_birth'     => ['nullable', 'date', 'before_or_equal:today'],
            'job_title'         => ['sometimes', 'required'],
            'email'             => ['sometimes', 'required', 'email']
        ];
    }
}
