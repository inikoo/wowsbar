<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:06:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Organisation;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $model_type
 */
class UniversalSearchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'model_type' => $this->model_type,
            'model'      => $this->when(true, function () {
                return match (class_basename($this->resource->model)) {
                    'CustomerWebsite'       => new CustomerWebsiteSearchResultResource($this->resource->model),
                    default                 => [],
                };
            }),

        ];
    }
}
