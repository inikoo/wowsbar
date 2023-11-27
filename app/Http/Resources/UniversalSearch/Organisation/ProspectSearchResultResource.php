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
            'slug'         => $prospect->slug,
            'name'         => $prospect->name,
            'company'      => $prospect->name == $prospect->company_name ? null : $prospect->company_name,
            'contact_name' => $prospect->name == $prospect->contact_name ? null : $prospect->contact_name,
            'email'        => $prospect->email,
            'phone'        => $prospect->phone,
            'website'      => $prospect->contact_website,
            'tags'         => $prospect->tags()->pluck('name'),
            'route'        => [
                'name'       => 'org.crm.shop.prospects.show',
                'parameters' => [$prospect->scope->slug, $prospect->slug]
            ],
            'state'        => $prospect->state,
            'state_icon'   => $prospect->state->stateIcon()[$prospect->state->value],
            'icon'         => ['fal', 'fa-transporter']


        ];
    }
}
