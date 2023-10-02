<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 19:40:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Web;

use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class WebpageBlocksResource extends JsonResource
{
    use HasSelfCall;


    public function toArray($request): array
    {
        $blocks = (array) $request;

        /*
        $media      = Media::find(Arr::get($blocks, 'logo'));
        if($media) {
            $logo = (new Image())->make($media->getImgProxyFilename())->resize(0, 64);
            return array_merge($blocks, ['logoSrc' => GetPictureSources::run($logo)]);
        }
        */
        return  $blocks;
    }
}
