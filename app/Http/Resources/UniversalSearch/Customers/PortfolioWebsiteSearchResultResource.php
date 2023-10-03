<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:04:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $name
 * @property string $code
 * @property string $domain
 */
class PortfolioWebsiteSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code'   => $this->code,
            'name'   => $this->name,
            'domain' => $this->domain,
            'route'  => [
                'name'       => 'customer.portfolio.websites.show',
                'parameters' => $this->slug
            ],
            'icon'   => ['fal', 'fa-globe'],
            'banner' => 4


        ];
    }
}
