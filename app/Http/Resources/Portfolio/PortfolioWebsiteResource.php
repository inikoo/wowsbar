<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class PortfolioWebsiteResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var PortfolioWebsite $websitePortfolio */
        $websitePortfolio = $this;

        return [
            'slug'           => $websitePortfolio->slug,
            'code'           => $websitePortfolio->code,
            'name'           => $websitePortfolio->name,
            'domain'         => $websitePortfolio->domain,
            'number_banners' => $this->number_banners
        ];
    }
}
