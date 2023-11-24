<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 23 Nov 2023 21:15:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Leads\Prospect;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProspectEmailClicked
{
    use AsAction;

    public function handle(Prospect $prospect, Carbon $date): void
    {
        $dataToUpdate = [
            'last_clicked_at' => $date
        ];

        if ($prospect->state == ProspectStateEnum::NO_CONTACTED or $prospect->state == ProspectStateEnum::CONTACTED) {
            $dataToUpdate['state']           = ProspectStateEnum::CONTACTED;
            $dataToUpdate['contacted_state'] = ProspectContactedStateEnum::CLICKED;
        }

        UpdateProspect::run(
            $prospect,
            $dataToUpdate
        );
    }
}
