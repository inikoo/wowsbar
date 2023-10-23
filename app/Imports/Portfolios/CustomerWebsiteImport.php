<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 17:18:44 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Imports\Portfolios;

use App\Actions\Portfolio\PortfolioDivision\SyncDivisionPortfolioWebsite;
use App\Actions\Subscriptions\CustomerWebsite\StoreCustomerWebsite;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteInterestEnum;
use App\Imports\WithImport;
use App\Models\CRM\Customer;
use App\Models\Organisation\Division;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerWebsiteImport implements ToCollection, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use WithImport;


    public function storeModel($row, $uploadRecord): void
    {
        $customer = Customer::where('slug', $row->get('customer'))->first();


        $fields =
            array_merge(
                array_keys(
                    Arr::except(
                        $this->rules(),
                        ['customer']
                    )
                ),
                [
                    'name',
                ]
            );


        try {
            $customerWebsite = StoreCustomerWebsite::make()->action(
                $customer,
                $row->only($fields)->all()
            );


            foreach (Division::all()->pluck('slug')->all() as $division) {
                if ($row->has($division)) {

                    $portfolioWebsite=PortfolioWebsite::find($customerWebsite->id);
                    SyncDivisionPortfolioWebsite::run(
                        $portfolioWebsite,
                        [
                            'division' => $division,
                            'interest' => $row->get($division)
                        ]
                    );

                }
            }


            $this->setRecordAsCompleted($uploadRecord);
        } catch (Exception $e) {
            $this->setRecordAsFailed($uploadRecord, [$e->getMessage()]);
        }
    }

    public function prepareForValidation($data)
    {
        $url = '';
        if (Arr::get($data, 'url')) {
            $url         = preg_replace('/^https?:\/\//', '', Arr::get($data, 'url'));
            $url         = preg_replace('/^www./', '', $url);
            $url         = preg_replace('/\/$/', '', $url);
            $url         = 'https://'.$url;
            $data['url'] = $url;
        }


        if (!Arr::exists($data, 'name') and $url) {
            $parse = parse_url($url);
            $name  = preg_replace("/^([a-zA-Z0-9].*\.)?([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z.]{2,})$/", '$2', $parse['host']);
            if ($name == '') {
                $name = 'website';
            }

            $data['name'] = $name;
        }

        foreach (Division::all()->pluck('slug')->all() as $division) {
            if (Arr::exists($data, $division)) {
                if (in_array(Arr::get($data, $division), ['C', 'c'])) {
                    $data[$division] = PortfolioWebsiteInterestEnum::CUSTOMER;
                }
                if (in_array(Arr::get($data, $division), ['I', 'i'])) {
                    $data[$division] = PortfolioWebsiteInterestEnum::INTERESTED;
                }
                if (in_array(Arr::get($data, $division), ['no', 'n', 'No', 'NO', 'N'])) {
                    $data[$division] = PortfolioWebsiteInterestEnum::NOT_INTERESTED;
                }
            }
        }


        return $data;
    }


    public function rules(): array
    {
        return [
            'customer' => ['required', 'exists:customers,slug'],

            'url'  => [
                'required',
                'url',
                'max:500',
            ],
            'name' => ['required', 'string', 'max:128'],
            'seo'  => ['sometimes', new Enum(PortfolioWebsiteInterestEnum::class)]

        ];
    }
}
