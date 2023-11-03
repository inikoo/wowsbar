<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 15:07:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Services\Aurora;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FetchAuroraProspect extends FetchAurora
{
    protected function parseModel(): void
    {
        $state = Str::kebab($this->auroraModelData->{'Prospect Status'});

        /*
        $customer_id = null;
        if ($this->auroraModelData->{'Prospect Customer Key'}) {
            $customer_id = $this->parseCustomer($this->auroraModelData->{'Prospect Customer Key'})->id;
        }
        */


        $email=$this->auroraModelData->{'Prospect Main Plain Email'};
        $email=preg_replace('/\.+/', '.', $email);

        $this->parsedData['prospect'] =
            [
                'state'           => $state,
                'contact_name'    => $this->auroraModelData->{'Prospect Main Contact Name'},
                'company_name'    => $this->auroraModelData->{'Prospect Company Name'},
                'email'           => $email,
                'phone'           => $this->auroraModelData->{'Prospect Main Plain Mobile'},
                'contact_website' => $this->auroraModelData->{'Prospect Website'},

                'data'=> [
                    'source'=> [
                        'source_type'     => 'aurora',
                        'source_id'       => $this->auroraModelData->{'Prospect Key'},
                    ]

                ],
                //'customer_id'     => $customer_id
                'address'=> $this->parseAddress(prefix: 'Prospect Contact', auAddressData: $this->auroraModelData)
            ];




        if($this->parseDate($this->auroraModelData->{'Prospect First Contacted Date'})) {

            $this->parsedData['prospect']['created_at']=$this->auroraModelData->{'Prospect First Contacted Date'};
        }





    }


    protected function fetchData($id): object|null
    {
        return DB::connection('aurora')
            ->table('Prospect Dimension')
            ->where('Prospect Key', $id)->first();
    }
}
