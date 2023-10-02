<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 21 Mar 2023 19:11:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Media;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Models\Media\Media;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowMedia
{
    use AsAction;


    public function handle(Media $media, $width, $height)
    {
        $image = (new Image())->make($this->avatar->getImgProxyFilename())->resize($width, $height);
        return GetPictureSources::run($image);
    }


    public function asController(Media $media, string $preset): Media
    {
        $width =0;
        $height=0;

        return $this->handle($media, $width, $height);
    }


    public function htmlResponse(Media $media)
    {
        $headers = [
            'Content-Type'   => $media->mime_type,
            'Content-Length' => $media->size,
        ];
        return response()->file($media->getPath(), $headers);
    }
}
