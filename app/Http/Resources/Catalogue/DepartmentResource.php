<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 16:03:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Catalogue;

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
            'code'               => $this->code,
            'name'               => $this->name,
            'state'              => $this->state,
            'interest'           => $this->interest,
            'description'        => $this->description,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
