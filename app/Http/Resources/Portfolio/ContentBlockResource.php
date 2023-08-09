<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $name
 * @property string $code
 * @property string $updated_at
 * @property $website
 */
class ContentBlockResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug'           => $this->slug,
            'code'           => $this->code,
            'name'           => $this->name,
            'updated_at'     => $this->updated_at,
            'route'          => [
                'name' => 'websites.show.banners.show',
                'parameters' => [$this->slug, $this->slug]
            ]
        ];
    }
}
