<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 07 Feb 2024 11:29:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Media\Media;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Models\Media\Media;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowMedia
{
    use AsAction;


    public function handle(Media $media, $width, $height)
    {
        $image = $media->getImage()->resize($width, $height);
        return GetPictureSources::run($image);
    }


    public function asController(Media $media, string $preset): Media
    {
        $width =0;
        $height=0;

        return $this->handle($media, $width, $height);
    }


    public function htmlResponse(Media $media): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $headers = [
            'Content-Type'   => $media->mime_type,
            'Content-Length' => $media->size,
        ];
        return response()->file($media->getPath(), $headers);
    }
}
