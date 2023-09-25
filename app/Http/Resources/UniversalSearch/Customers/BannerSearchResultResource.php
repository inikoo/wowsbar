<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:26:15 Malaysia Time, Kuala Lumpur, Malysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Customers;

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
        /** @var Banner $contentBlock */
        $contentBlock=$this;
        return [
            'code'           => $contentBlock->code,
            'name'           => $contentBlock->name,
            'route'          => [
                'name'       => 'portfolio.websites.show.banners.show',
                'parameters' => [
                    $contentBlock->data['website_slug'],
                    $contentBlock->slug
                ]
            ],
            'icon'   => ['fal', 'fa-window-maximize']
        ];
    }
}
