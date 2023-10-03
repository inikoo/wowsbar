<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:26:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Customers;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Helpers\ImgProxy\Image;
use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $name
 * @property string $code
 */
class BannerSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Banner $banner */
        $banner=$this;

        $imageThumbnail = null;
        if ($banner->image) {
            $imageThumbnail = (new Image())->make($banner->image->getImgProxyFilename())->resize(0, 48);
        }

        return [
            'code'            => $banner->slug,
            'name'            => $banner->name,
            'image_thumbnail' => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,

            'state_icon'      => match ($banner->state) {
                BannerStateEnum::LIVE => [

                    'tooltip' => __('live'),
                    'icon'    => 'fal fa-broadcast-tower',
                    'class'   => 'text-green-600 animate-pulse'

                ],
                BannerStateEnum::UNPUBLISHED => [

                    'tooltip' => __('unpublished'),
                    'icon'    => 'fal fa-seedling',
                    'class'   => 'text-indigo-500'


                ],
                BannerStateEnum::RETIRED => [

                    'tooltip' => __('retired'),
                    'icon'    => 'fal fa-eye-slash'

                ]
            },
            'route'          => [
                'name'       => 'customer.banners.show',
                'parameters' => [
                    $banner->slug
                ]
            ],
            'icon'   => ['fal', 'fa-rectangle-wide']
        ];
    }
}
