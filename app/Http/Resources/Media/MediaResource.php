<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 13:07:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;


class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Media\Media $media */
        $media = $this;

        return [
            'id'        => $media->id,
            'uuid'      => $media->uuid,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'size'      => $media->size,
            'checksum'  => $media->checksum,
            'url'       => route('media.show', $media->id)


        ];
    }
}
