<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 17 Nov 2023 13:36:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Organisation;

use App\Models\Leads\Prospect;
use Illuminate\Http\Resources\Json\JsonResource;

class ProspectSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Prospect $prospect */
        $prospect = $this;

        return [
            'slug'  => $prospect->slug,
            'name'  => $prospect->name,
            'email' => $prospect->email,
            'phone' => $prospect->phone,
            'tags'  => $prospect->tags()->pluck('name'),
            'route' => [
                'name'       => 'org.crm.shop.prospects.show',
                'parameters' => [$prospect->scope_id, $prospect->slug]
            ],
            'state'      => $prospect->state,
            'state_icon' => $prospect->state->stateIcon()[$prospect->state->value],
            'icon'       => ['fal', 'fa-transporter']


        ];
    }
}
