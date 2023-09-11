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
use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Media\Media $media */
        $media = $this;


        $image          = (new Image())->make($media->getImgProxyFilename(), $media->is_animated);
        $imageThumbnail = (new Image())->make($media->getImgProxyFilename(), $media->is_animated)->resize(0, 48);

        return [
            'id'         => $media->id,
            'slug'       => $media->slug,
            'name'       => $media->name,
            'mime_type'  => $media->mime_type,
            'size'       => NaturalLanguage::make()->fileSize($media->size),
            'thumbnail'  => GetPictureSources::run($imageThumbnail),
            'source'     => GetPictureSources::run($image),
            'created_at' => $media->created_at,
        ];
    }
}
