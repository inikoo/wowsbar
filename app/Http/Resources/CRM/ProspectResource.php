<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\CRM;

use App\Http\Resources\Market\ShopResource;
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
 * @property \App\Models\Market\Shop $shop
 */
class ProspectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug'       => $this->slug,
            'shop'       => new ShopResource($this->shop),
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'website'    => $this->contact_website,
            'tags'       => $this->tags()->pluck('name'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
