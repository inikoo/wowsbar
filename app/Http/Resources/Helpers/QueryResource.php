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
        return [
            'slug'       => $this->slug,
            'name'       => $this->name,
            'model_type' => $this->model_type,
            'constrains' => $this->constrains,
            'arguments'  => $this->arguments,
            'is_seeded'  => $this->is_seeded
        ];
    }
}
