<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 14:08:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\ContentBlockComponent;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentBlockComponentResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ContentBlockComponent $contentBlockComponent */
        $contentBlockComponent = $this;

        return [
            'id'           => $contentBlockComponent->id,
            'ulid'         => $contentBlockComponent->ulid,
            'layout'       => $contentBlockComponent->layout,
            'visibility'   => $contentBlockComponent->visibility,
            'image_id'     => $contentBlockComponent->image_id,
            'image_source' => route('media.show', $contentBlockComponent->image_id)
        ];
    }
}
