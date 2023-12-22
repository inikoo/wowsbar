<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:26:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\PortfolioWebpage;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioWebpageResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var PortfolioWebpage $portfolioWebpage */
        $portfolioWebpage = $this;

        return [
            'id'    => $portfolioWebpage->id,
            'slug'  => $portfolioWebpage->slug,
            'title' => $portfolioWebpage->title,
            'url'   => preg_replace('/^https?:\/\//', '', $portfolioWebpage->url),
        ];
    }
}
