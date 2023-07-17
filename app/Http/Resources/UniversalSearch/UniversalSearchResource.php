<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\UniversalSearch;

use App\Http\Resources\Auth\UserSearchResultResource;
use App\Http\Resources\Portfolio\ContentBlockSearchResultResource;
use App\Http\Resources\Portfolio\WebsiteSearchResultResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $secondary_term
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property string $primary_term
 * @property string $model_id
 * @property string $model_type
 * @property string $icon
 * @property string $route
 * @property string $section
 */
class UniversalSearchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'model_type' => $this->model_type,
            'model'      => $this->when(true, function () {
                return match (class_basename($this->resource->model)) {
                    'Website'      => new WebsiteSearchResultResource($this->resource->model),
                    'ContentBlock' => new ContentBlockSearchResultResource($this->resource->model),
                    'User'         => new UserSearchResultResource($this->resource->model),
                    default        => [],
                };
            }),

        ];
    }
}
