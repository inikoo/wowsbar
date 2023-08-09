<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 10:44:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Gallery;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Helpers\NaturalLanguage;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Media\Media $media */
        $media = $this;

        $image = (new Image)->make(route('media.show', $media->id))->resize(0, 24);


        return [
            'id'        => $media->id,
            'name'      => $media->name,
            'mime_type' => $media->mime_type,
            'size'      => NaturalLanguage::make()->fileSize($media->size),
            'thumbnail'       => GetPictureSources::run($image)


        ];
    }
}
