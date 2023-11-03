<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 16:16:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Models\Leads\Prospect;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $code
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $contact_website
 * @property \Spatie\Tags\Tag $tags
 */
class ProspectsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Prospect $prospect */
        $prospect = $this;



        return [
            'slug'       => $prospect->slug,
            'name'       => $prospect->name,
            'email'      => $this->email,
            'phone'      => $prospect->phone,
            'website'    => $prospect->contact_website,
            'tags'       => $prospect->tags()->pluck('name'),
            'state'      => $prospect->state,
            'state_icon' => $prospect->state->stateIcon()[$prospect->state->value],

        ];
    }
}
