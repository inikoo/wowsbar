<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Sep 2023 01:20:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\CRM;

use App\Actions\CRM\Customer\StoreCustomer;
use App\Imports\WithImport;
use App\Models\Market\Shop;
use Exception;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use WithImport;

    public function storeModel($row, $uploadRecord): void
    {
        $shop = Shop::where('slug', $row->get('shop'))->first();


        $row->put('company_name', $row->get('company'));

        $row->put('contact_website', $row->get('website'));

        $fields =
            array_merge(
                Arr::except(
                    array_keys($this->rules()),
                    ['shop', 'name', 'website']
                ),
                [
                    'company_name',
                    'contact_website'
                ]
            );


        try {
            StoreCustomer::make()->action(
                $shop,
                $row->only($fields)->all()
            );
            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }


    public function rules(): array
    {
        return [
            'shop' => ['required', 'exists:shops,slug'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email','unique:customers'],
            'phone' => ['nullable'],
            'website' => ['nullable']
        ];
    }
}
