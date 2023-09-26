<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 16:03:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Catalogue;

use App\Models\Catalogue\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ProductCategory $department */
        $department=$this;

        return [
            'id'          => $department->id,
            'slug'        => $department->slug,
            'code'        => $department->code,
            'name'        => $department->name,
            'state'       => $department->state,
            'description' => $department->description,
            'created_at'  => $department->created_at,
            'updated_at'  => $department->updated_at,
        ];
    }
}
