<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\Mail;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $data
 * @property string $name
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 */
class OutboxResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug'                           => $this->slug,
            'data'                           => $this->data,
            'name'                           => $this->name,
            'created_at'                     => $this->created_at,
            'updated_at'                     => $this->updated_at,
        ];
    }
}
