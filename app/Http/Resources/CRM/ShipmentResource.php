<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 21:20:25 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Shipment $shipment */
        $shipment = $this;

        return [
            'reference' => $shipment->reference,
            'status'    => $shipment->status
        ];
    }
}
