<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 09 Jan 2024 14:27:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProspectEmailUndoUnsubscribed
{
    use AsAction;

    public function handle(Prospect $prospect, Carbon $date): void
    {
        $dataToUpdate = [
            'dont_contact_me'   => false,
            'dont_contact_me_at'=> null,
            'failed_at'         => null,
        ];


        /*

        if ($prospect->state != ProspectStateEnum::SUCCESS) {
            $dataToUpdate['state']           = ProspectStateEnum::FAIL;
            $dataToUpdate['fail_status']     = ProspectFailStatusEnum::UNSUBSCRIBED;
            $dataToUpdate['contacted_state'] = ProspectFailStatusEnum::NA;
        }
        */

        UpdateProspect::run(
            $prospect,
            $dataToUpdate
        );
    }

    public function inShop(Shop $shop, Prospect $prospect): void
    {
        $this->handle($prospect, now());
    }
}
