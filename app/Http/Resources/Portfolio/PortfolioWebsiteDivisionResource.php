<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioWebsiteDivisionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\SysAdmin\Division $division */
        $division = $this;

        return [
            $division->slug => [
                'name'  => $division->slug,
                'label' => $division->name,
                'value' => $division->pivot->interest
            ]
        ];
    }
}
