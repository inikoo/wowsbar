<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Prospects;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $customer_name
 * @property string $customer_slug
 */
class CustomerWebsiteResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Portfolios\CustomerWebsite $customerWebsite */
        $customerWebsite = $this;


        return [
            'slug'           => $customerWebsite->slug,
            'customer_name'  => $this->customer_name,
            'customer_slug'  => $this->customer_slug,

            'name'           => $customerWebsite->name,
            'url'         => $customerWebsite->url,
           // 'number_banners' => $customerWebsite->stats->number_banners
        ];
    }
}
