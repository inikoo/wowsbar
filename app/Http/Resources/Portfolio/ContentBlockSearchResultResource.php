<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $name
 * @property string $code
 */
class ContentBlockSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Banner $contentBlock */
        $contentBlock=$this;
        return [
            'code'           => $contentBlock->code,
            'name'           => $contentBlock->name,
            'route'          => [
                'name'       => 'portfolio.portfolio-websites.show.banners.show',
                'parameters' => [
                    $contentBlock->data['website_slug'],
                    $contentBlock->slug
                ]
            ],
            'icon'   => ['fal', 'fa-terminal']
        ];
    }
}
