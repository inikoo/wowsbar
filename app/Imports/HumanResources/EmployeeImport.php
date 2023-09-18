<?php

namespace App\Imports\HumanResources;

use App\Actions\Organisation\HumanResources\Employee\ImportEmployees;
use App\Actions\Organisation\HumanResources\Employee\UpdateEmployeeUploads;
use App\Models\Media\ExcelUpload;
use App\Models\Media\ExcelUploadRecord;
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

        UpdateEmployeeUploads::run($this->employeeUpload, ['number_rows' => count($collection)]);

        foreach ($collection as $employee) {
            try {
                $employee = ExcelUploadRecord::create([
                        'excel_upload_id' => $this->employeeUpload->id,
                        'data'   => json_encode([
                        'contact_name'    => $employee['contact_name'],
                        'date_of_birth'   => $employee['date_of_birth'],
                        'job_title' => $employee['job_title'],
                        'state' => $employee['state'],
                        'email' => $employee['email'],
                    ])
                ]);

                ImportEmployees::dispatch($employee, count($collection), $totalImported++);
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
