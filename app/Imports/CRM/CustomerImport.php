<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Sep 2023 01:20:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\CRM;

use App\Actions\Auth\User\StoreUser;
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
                array_keys(
                    Arr::except(
                        $this->rules(),
                        ['shop', 'name', 'website', 'password', 'reset_password']
                    )
                ),
                [
                    'company_name',
                    'contact_website'
                ]
            );


        try {
            $customer = StoreCustomer::make()->action(
                $shop,
                $row->only($fields)->all()
            );

            if (Arr::get($row, 'password') and Arr::get($row, 'email')) {
                StoreUser::make()->action(
                    $shop->website,
                    $customer,
                    array_merge(
                        $row->only(['email', 'password', 'reset_password'])->all(),
                        [
                            'contact_name' => $customer->contact_name,
                            'is_root'      => true,

                        ]
                    )
                );
            }

            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        } catch (\Throwable $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }


    public function rules(): array
    {
        return [
            'shop'           => ['required', 'exists:shops,slug'],
            'contact_name'   => ['nullable', 'string', 'max:255'],
            'company'        => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'iunique:customers', 'iunique:users'],
            'phone'          => ['nullable', 'phone:AUTO'],
            'website'        => ['nullable'],
            'password'       => ['sometimes', 'string', 'min:8', 'max:64'],
            'reset_password' => ['sometimes', 'boolean'],

        ];
    }
}
