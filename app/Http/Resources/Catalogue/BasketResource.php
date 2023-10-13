<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 16:03:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Catalogue;

use App\Models\Catalogue\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property object $pivot
 */

class BasketResource extends JsonResource
{
    public function toArray($request): array
    {
        $divisions = $this;

        return [
            'id'          => $divisions->id,
            'slug'        => $divisions->slug,
            'name'        => $divisions->name,
            'interest'    => $divisions->pivot->interest
        ];
    }
}
