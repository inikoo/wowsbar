<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $live_snapshot_id
 */
class BannerResource extends JsonResource
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

        $publishedSnapshot = [];
        if ($banner->state == BannerStateEnum::LIVE and $this->live_snapshot_id) {
            $snapshot          = $banner->liveSnapshot;
            $publishedSnapshot = SnapshotResource::make($snapshot)->getArray();
        }

        return [
            'id'                 => $banner->id,
            'type'               => $banner->type,
            'ulid'               => $banner->ulid,
            'slug'               => $banner->slug,
            'name'               => $banner->name,
            'state'              => $banner->state,
            'state_label'        => $banner->state->labels()[$banner->state->value],
            'state_icon'         => match ($banner->state) {
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
            'image_thumbnail'    => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,
            'image'              => $image ? GetPictureSources::run($image) : null,
            'route'              => [
                'name'       => 'customer.caas.banners.show',
                'parameters' => [$banner->slug]
            ],
            'websites'           => implode(', ', $banner->portfolioWebsite->pluck('name')->toArray()),
            'updated_at'         => $banner->updated_at,
            'created_at'         => $banner->created_at,
            'workshopRoute'      => [
                'name'       => 'customer.caas.banners.workshop',
                'parameters' => [$banner->slug]
            ],
            'compiled_layout'    => $banner->compiled_layout,
            'delivery_url'       => config('app.delivery_url').$banner->ulid,
            'published_snapshot' => $publishedSnapshot
        ];
    }
}
