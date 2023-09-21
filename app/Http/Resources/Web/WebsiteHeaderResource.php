<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 14:16:18 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Web;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use App\Models\Media\Media;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class WebsiteHeaderResource extends JsonResource
{
    use HasSelfCall;


    public function toArray($request): array
    {
        $headerData = $request;


        $media = Media::find(Arr::get($headerData, 'logo'));


        $logo = (new Image())->make($media->getImgProxyFilename())->resize(0, 64);


        return [
            'logo' => GetPictureSources::run($logo)
        ];
    }
}
