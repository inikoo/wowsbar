<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 15:06:49 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Web;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use App\Models\Media\Media;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class WebsiteLayoutResource extends JsonResource
{
    use HasSelfCall;


    public function toArray($request): array
    {
        $layoutData = (array) $request;



        $media      = Media::find(Arr::get($layoutData, 'favicon'));
        if($media) {
            $favicon    = (new Image())->make($media->getImgProxyFilename())->resize(0, 180);
            return array_merge($layoutData, ['favicon' => GetPictureSources::run($favicon)]);

        }
        return  $layoutData;

    }
}
