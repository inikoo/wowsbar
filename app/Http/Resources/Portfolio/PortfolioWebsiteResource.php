<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioWebsiteResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var PortfolioWebsite $portfolioWebsite */
        $portfolioWebsite = $this;
        $divisions        = [];

        foreach ($portfolioWebsite->divisions as $value) {
            $divisions[$value->slug] = [
                'name'  => $value->slug,
                'label' => $value->name,
                'value' => $value->pivot->interest
            ];
        }

        return array_merge([
            'id'   => $portfolioWebsite->id,
            'slug' => $portfolioWebsite->slug,
            'name' => $portfolioWebsite->name,
            'url'  => preg_replace('/^https?:\/\//', '', $portfolioWebsite->url),
        ], $divisions);
    }
}
