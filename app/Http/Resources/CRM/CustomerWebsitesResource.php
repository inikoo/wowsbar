<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Dec 2023 21:55:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Models\CRM\CustomerWebsite;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $number_banners
 * @property integer $number_portfolio_webpages
 */
class CustomerWebsitesResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var CustomerWebsite $customerWebsite */
        $customerWebsite = $this;


        return [
            'slug'                      => $customerWebsite->slug,
            'name'                      => $customerWebsite->name,
            'url'                       => $customerWebsite->url,
            'number_banners'            => $this->number_banners,
            'number_portfolio_webpages' => $this->number_portfolio_webpages
        ];
    }
}
