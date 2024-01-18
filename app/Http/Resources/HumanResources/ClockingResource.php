<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\HumanResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClockingResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\HumanResources\Clocking $clocking */
        $clocking = $this;

        return [
            'id'            => $clocking->id,
            'slug'          => $clocking->slug,
            'status'        => $clocking->timeTracking->status
        ];
    }
}
