<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Banner $banner */
        $banner=$this;
        return [
            'slug'           => $banner->slug,
            'code'           => $banner->code,
            'name'           => $banner->name,
            'route'          => [
                'name'       => 'portfolio.banners.show',
                'parameters' => [$banner->slug]
            ],
         //   'components'     => SlideResource::collection($banner->slides),
            'updated_at'     => $banner->updated_at
        ];
    }
}
