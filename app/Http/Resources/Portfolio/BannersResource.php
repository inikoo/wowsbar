<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 15 Oct 2023 09:10:43 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $websites
 */
class BannersResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Banner $banner */
        $banner = $this;

        $image          = null;
        $imageThumbnail = null;
        if ($banner->image) {
            $image          = (new Image())->make($banner->image->getImgProxyFilename());
            $imageThumbnail = (new Image())->make($banner->image->getImgProxyFilename())->resize(0, 48);
        }

        return [
            'type'               => $banner->type,
            'slug'               => $banner->slug,
            'name'               => $banner->name,
            'state'              => $banner->state,
            'state_label'        => $banner->state->labels()[$banner->state->value],
            'state_icon'         => $banner->state->stateIcon()[$banner->state->value],
            'date_icon'          => $banner->state->dateIcon()[$banner->state->value],
            'image_thumbnail'    => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,
            'image'              => $image ? GetPictureSources::run($image) : null,
            'websites'           => json_decode($this->websites),
            'date'               => $banner->date,
            'number_views'       => $banner->number_views,
            'delivery_url'       => config('app.delivery_url').'/banners/'.$banner->ulid,


        ];
    }
}
