<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Oct 2022 15:56:55 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Market;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $code
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $warehouse_area_slug
 * @property mixed $type
 */
class ShopResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'      => $this->id,
            'slug'    => $this->slug,
            'code'    => $this->code,
            'name'    => $this->name,
            'type'    => $this->type,

        ];
    }
}
