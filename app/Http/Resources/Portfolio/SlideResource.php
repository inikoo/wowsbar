<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 14:08:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Http\Resources\Gallery\ImageResource;
use App\Models\Portfolio\Slide;
use Illuminate\Http\Resources\Json\JsonResource;

class SlideResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Slide $contentBlockComponent */
        $contentBlockComponent = $this;

        return [
            'id'         => $contentBlockComponent->id,
            'ulid'       => $contentBlockComponent->ulid,
            'layout'     => $contentBlockComponent->layout,
            'visibility' => $contentBlockComponent->visibility,
            'image'      => ImageResource::make($contentBlockComponent->image)->getArray(),

        ];
    }
}
