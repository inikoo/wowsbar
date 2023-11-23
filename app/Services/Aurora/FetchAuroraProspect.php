<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 15:07:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Services\Aurora;

use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\CRM\Prospect\ProspectSuccessStatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FetchAuroraProspect extends FetchAurora
{
    protected function parseModel(): void
    {
        $lastContacted = null;
        if ($this->parseDatetime($this->auroraModelData->{'Prospect Last Contacted Date'})) {
            $lastContacted = $this->parseDatetime($this->auroraModelData->{'Prospect Last Contacted Date'});
        }


        // $state = Str::kebab($this->auroraModelData->{'Prospect Status'});
        //enum('NoContacted','Contacted','NotInterested','Registered','Invoiced','Bounced')

        $dontContactMe  = false;
        $contactedState = ProspectContactedStateEnum::NA;
        $failStatus     = ProspectFailStatusEnum::NA;
        $successStatus  = ProspectSuccessStatusEnum::NA;
        switch ($this->auroraModelData->{'Prospect Status'}) {
            case 'NoContacted':
                $state         = ProspectStateEnum::NO_CONTACTED;
                $lastContacted = null;
                break;
            case 'Contacted':
                $state          = ProspectStateEnum::CONTACTED;
                $contactedState = ProspectContactedStateEnum::NEVER_OPEN;
                break;
            case 'NotInterested':
                $state         = ProspectStateEnum::FAIL;
                $failStatus    = ProspectFailStatusEnum::UNSUBSCRIBED;
                $dontContactMe = true;
                break;
            case 'Registered':
                $state         = ProspectStateEnum::SUCCESS;
                $successStatus = ProspectSuccessStatusEnum::REGISTERED;
                break;
            case 'Invoiced':
                $state         = ProspectStateEnum::SUCCESS;
                $successStatus = ProspectSuccessStatusEnum::INVOICED;
                break;
            case 'Bounced':
                $state      = ProspectStateEnum::FAIL;
                $failStatus = ProspectFailStatusEnum::INVALID;
                break;
            default:
                dd(Str::kebab($this->auroraModelData->{'Prospect Status'}));
        }


        /*
        $customer_id = null;
        if ($this->auroraModelData->{'Prospect Customer Key'}) {
            $customer_id = $this->parseCustomer($this->auroraModelData->{'Prospect Customer Key'})->id;
        }
        */


        $email = $this->auroraModelData->{'Prospect Main Plain Email'};
        $email = preg_replace('/\.+/', '.', $email);

        $this->parsedData['prospect'] =
            [
                'state'             => $state,
                'contacted_state'   => $contactedState,
                'fail_status'       => $failStatus,
                'success_status'    => $successStatus,
                'dont_contact_me'   => $dontContactMe,
                'last_contacted_at' => $lastContacted,
                'contact_name'      => $this->auroraModelData->{'Prospect Main Contact Name'},
                'company_name'      => $this->auroraModelData->{'Prospect Company Name'},
                'email'             => $email,
                'phone'             => $this->auroraModelData->{'Prospect Main Plain Mobile'},
                'contact_website'   => $this->auroraModelData->{'Prospect Website'},

                'data'    => [
                    'source' => [
                        'source_type' => 'aurora',
                        'source_id'   => $this->auroraModelData->{'Prospect Key'},
                    ]

                ],
                //'customer_id'     => $customer_id
                'address' => $this->parseAddress(prefix: 'Prospect Contact', auAddressData: $this->auroraModelData)
            ];

        if ($this->parseDatetime($this->auroraModelData->{'Prospect Created Date'})) {
            $this->parsedData['prospect']['created_at'] = $this->auroraModelData->{'Prospect Created Date'};
        }

        //print_r($this->parsedData['prospect']);

    }


    protected function fetchData($id): object|null
    {
        return DB::connection('aurora')
            ->table('Prospect Dimension')
            ->where('Prospect Key', $id)->first();
    }
}
