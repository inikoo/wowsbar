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

class WebsiteFooterResource extends JsonResource
{
    use HasSelfCall;


    public function toArray($request): array
    {
        $footerData = (array)$request;
        $media = Media::find(Arr::get($footerData, 'logo'));
        if ($media) {
            $logo = (new Image())->make($media->getImgProxyFilename())->resize(0, 120);
            $footerData = array_merge($footerData, ['logoSrc' => GetPictureSources::run($logo)]);
        }

        return $footerData;
    }
}
