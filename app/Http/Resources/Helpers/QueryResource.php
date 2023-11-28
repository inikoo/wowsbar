<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 12 Jun 2023 13:57:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @property string $slug
 * @property string $name
 * @property string $model_type
 * @property mixed $constrains
 * @property mixed $arguments
 * @property boolean $is_seeded
 *
 */
class QueryResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Helpers\Query $query */
        $query = $this;

        return [
            'slug'          => $query->slug,
            'name'          => $query->name,
            'model_type'    => $query->model_type,
            'constrains'    => $query->constrains,
            'has_arguments' => $query->has_arguments,
            'is_seeded'     => $query->is_seeded
        ];
    }
}
