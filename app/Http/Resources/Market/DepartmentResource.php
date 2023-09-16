<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\Market;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $shop_slug
 * @property string $department_slug
 * @property string $code
 * @property string $name
 * @property string $state
 * @property string $description
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class DepartmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug'            => $this->slug,
            'shop_slug'       => $this->whenHas('shop_slug'),

            //'department_slug' => $this->department_slug,
            'code'            => $this->code,
            'name'            => $this->name,
            'state'           => $this->state,
            'description'     => $this->description,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
