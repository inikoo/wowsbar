<?php

namespace App\Imports\Auth;

use App\Actions\SysAdmin\Guest\StoreGuest;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Imports\WithImport;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuestImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation, WithEvents
{
    use WithImport;

    public function storeModel($row, $uploadRecord): void
    {
        $row->put('contact_name', $row->get('name'));


        $fields =
            array_merge(
                array_keys(
                    Arr::except(
                        $this->rules(),
                        ['name']
                    )
                ),
                [
                    'contact_name',
                ]
            );


        try {
            $modelData = $row->only($fields)->all();

            data_set($modelData, 'data.bulk_import', [
                'id'   => $this->upload->id,
                'type' => 'Upload',
            ]);

            StoreGuest::make()->action($modelData);
            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }

    public function prepareForValidation($data)
    {
        if (Arr::exists($data, 'username')) {
            $data['username'] = Str::lower($data['username']);
        }


        $data['positions'] = explode(',', Arr::get($data, 'positions'));


        return $data;
    }


    public function rules(): array
    {
        return [
            'type'            => ['required', Rule::in(GuestTypeEnum::values())],
            'username'        => ['required', 'iunique:organisation_users', 'alpha_dash:ascii'],
            'password'        => ['sometimes', 'string', 'min:8', 'max:64'],
            'reset_password'  => ['sometimes', 'boolean'],
            'company_name'    => ['nullable', 'string', 'max:255'],
            'name'            => ['required', 'string', 'max:255'],
            'phone'           => ['nullable'],
            'email'           => ['nullable', 'email'],
            'positions'       => ['required', 'array'],
            'positions.*'     => ['exists:job_positions,slug'],
            'alias'           => ['required', 'iunique:guests', 'string', 'max:16'],
        ];
    }
}
