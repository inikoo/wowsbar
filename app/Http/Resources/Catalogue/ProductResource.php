<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 16:03:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Catalogue;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $code
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property string $name
 * @property string $state
 * @property string $shop_slug
 *
 */
class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug'       => $this->slug,
            'code'       => $this->code,
            'name'       => $this->name,
            'state'      => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'shop_slug'  => $this->shop_slug
        ];
    }
}
