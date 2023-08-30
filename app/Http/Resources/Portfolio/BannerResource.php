<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Banner $banner */
        $banner = $this;

        $image          = null;
        $imageThumbnail = null;
        if ($banner->image) {
            $image          = (new Image())->make($banner->image->getLocalImgProxyFilename());
            $imageThumbnail = (new Image())->make($banner->image->getLocalImgProxyFilename())->resize(0, 48);
        }

        return [
            'slug'            => $banner->slug,
            'code'            => $banner->code,
            'name'            => $banner->name,
            'state'           => $banner->state,
            'image_thumbnail' => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,
            'image'           => $image ? GetPictureSources::run($image) : null,
            'route'           => [
                'name'       => 'portfolio.banners.show',
                'parameters' => [$banner->slug]
            ],
            'websites'        => implode(', ', $banner->portfolioWebsite->pluck('name')->toArray()),
            'updated_at'      => $banner->updated_at
        ];
    }
}
