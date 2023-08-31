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
        /** @var Slide $slide */
        $slide = $this;

        return [
            'id'         => $slide->id,
            'ulid'       => $slide->ulid,
            'layout'     => $slide->layout,
            'visibility' => $slide->visibility,
            'image'      => [
                'desktop'=>ImageResource::make($slide->image)->getArray(),
                'mobile'=>$slide->mobile_image_id?ImageResource::make($slide->imageMobile)->getArray():null,
                'tablet'=>$slide->tablet_image_id?ImageResource::make($slide->imageTablet)->getArray():null
            ]
        ];
    }
}
