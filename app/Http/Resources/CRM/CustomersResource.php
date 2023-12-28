<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Models\CRM\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $number_banners
 * @property string $number_portfolio_websites
 * @property string $number_portfolio_webpages
 */
class CustomersResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Customer $customer */
        $customer = $this;

        return [
            'slug'                      => $customer->slug,
            'reference'                 => $customer->reference,
            'name'                      => $customer->name,
            'number_banners'            => $this->number_banners,
            'number_portfolio_websites' => $this->number_portfolio_websites,
            'number_portfolio_webpages' => $this->number_portfolio_webpages,
        ];
    }
}
