<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 21:20:25 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Enums\CRM\Appointment\AppointmentEventEnum;
use App\Enums\CRM\Appointment\AppointmentStateEnum;
use App\Models\CRM\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipperAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\ShipperAccount $shipperAccount */
        $shipperAccount = $this;

        return [
            'slug'  => $shipperAccount->slug,
            'label' => $shipperAccount->label
        ];
    }
}
