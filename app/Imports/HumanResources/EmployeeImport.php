<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 23:37:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\HumanResources;

use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Imports\WithImport;
use App\Models\HumanResources\Workplace;
use Arr;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use WithImport;

    public function storeModel($row, $uploadRecord): void
    {
        $parent = organisation();
        if ($row['workplace'] and $workplace = Workplace::where('slug', $row['workplace'])->first()) {
            $parent = $workplace;
        }

        $row->put('contact_name', $row->get('name'));
        $row->put('employment_start_at', $row->get('starting_date'));


        $fields =
            array_merge(
                Arr::except(
                    array_keys($this->rules()),
                    ['name', 'starting_date', 'workplace', 'position_code']
                ),
                [
                    'contact_name',
                    'employment_start_at'
                ]
            );


        try {
            StoreEmployee::make()->action(
                $parent,
                $row->only($fields)->all()
            );
            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }

    public function prepareForValidation($data)
    {
        $data['starting_date'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['starting_date'])->format('Y-m-d');

        return $data;
    }


    public function rules(): array
    {
        return [
            'worker_number' => ['required', 'max:64', 'iunique:employees', 'alpha_dash:ascii'],
            'date_of_birth' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'work_email'    => ['sometimes', 'required', 'email'],
            'alias'         => ['required', 'iunique:employees', 'string', 'max:16'],
            'name'          => ['required', 'string', 'max:256'],
            'job_title'     => ['required', 'string', 'max:256'],
            'position_code' => ['required', 'exists:job_positions,slug'],
            'starting_date' => ['required', 'date'],
            'workplace'     => ['required', 'nullable', 'string', 'exists:workplaces,slug'],
        ];
    }


}
