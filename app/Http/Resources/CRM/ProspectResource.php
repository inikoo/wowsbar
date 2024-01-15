<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\CRM;

use App\Http\Resources\HasSelfCall;
use App\Http\Resources\Market\ShopResource;
use App\Models\Leads\Prospect;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \Spatie\Tags\Tag $tags
 * @property \App\Models\Market\Shop $shop
 */
class ProspectResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Prospect $prospect */
        $prospect = $this;

        return [
            'slug'               => $prospect->slug,
            'shop'               => new ShopResource($prospect->shop),
            'name'               => $prospect->name,
            'email'              => $prospect->email,
            'phone'              => $prospect->phone,
            'website'            => $prospect->contact_website,
            'tags'               => $prospect->tags()->pluck('name'),
            'created_at'         => $prospect->created_at,
            'updated_at'         => $prospect->updated_at,
            'state'              => $prospect->state,
            'state_icon'         => $prospect->state->stateIcon()[$prospect->state->value],
            'fail_status'        => $prospect->fail_status,
            'fail_status_icon'   => $prospect->fail_status->statusIcon()[$prospect->fail_status->value],
            'dont_contact_me_at' => $prospect->dont_contact_me_at,
            'dont_contact_me'    => $prospect->dont_contact_me,
            'contacted_state'    => $prospect->contacted_state,
            'success_status'     => $prospect->success_status,

            'last_contacted_at'    => $prospect->last_contacted_at,
            'last_opened_at'       => $prospect->last_opened_at,
            'last_clicked_at'      => $prospect->last_clicked_at,
            'failed_at'            => $prospect->failed_at,
            'registered_at'        => $prospect->registered_at,
            'invoiced_at'          => $prospect->invoiced_at,
            'last_soft_bounced_at' => $prospect->last_soft_bounced_at,

        ];
    }
}
