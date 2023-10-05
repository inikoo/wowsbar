<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:26:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\Leads;

use App\Actions\Leads\Prospect\StoreProspect;
use App\Imports\WithImport;
use App\Models\Helpers\Upload;
use App\Models\Market\Shop;
use App\Rules\IUnique;
use Exception;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProspectImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use WithImport;

    protected Shop $scope;
    public function __construct(Shop $scope, Upload $upload)
    {
        $this->scope  = $scope;
        $this->upload = $upload;
    }


    public function storeModel($row, $uploadRecord): void
    {

        $fields =
            array_merge(
                Arr::except(
                    array_keys($this->rules()),
                    []
                ),
                [
                ]
            );


        try {
            $modelData = $row->only($fields)->all();
            data_set($modelData, 'phone', null, overwrite: false);
            data_set($modelData, 'contact_website', null, overwrite: false);


            StoreProspect::make()->action(
                $this->scope,
                $modelData
            );
            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }

    public function prepareForValidation($data)
    {

        if (Arr::get($data, 'contact_website')) {

            if(!preg_match('/^https?:\/\//','')){
                $data['contact_website'] = 'https://'.$data['contact_website'];

            }
        }



        return $data;
    }



    public function rules(): array
    {

        $extraConditions = match (class_basename($this->scope)) {
            'Shop' => [
                ['column' => 'shop_id', 'value' => $this->scope->id],
            ],
            default => []
        };

        return [
            'contact_name'    => ['required', 'nullable', 'string', 'max:255'],
            'company_name'    => ['required', 'nullable', 'string', 'max:255'],
            'email'           => [
                'present','nullable',
                'email',
                'max:500',


            ],
            'phone'           => [
                'present',
                'nullable',
                'phone:AUTO',
            ],
            'contact_website' => ['nullable', 'url',

            ],
        ];
    }
}
