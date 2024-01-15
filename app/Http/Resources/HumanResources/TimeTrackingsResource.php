<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Dec 2023 02:34:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\HumanResources;

use App\Models\HumanResources\TimeTracking;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class TimeTrackingsResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var TimeTracking $timeTracking */
        $timeTracking = $this;


        return [
            'id'                  => $timeTracking->id,
            'slug'                => $timeTracking->slug,
        ];
    }
}
