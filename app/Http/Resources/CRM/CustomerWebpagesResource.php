<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Dec 2023 23:48:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Models\CRM\CustomerWebpage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $number_banners
 * @property integer $number_portfolio_webpages
 */
class CustomerWebpagesResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var CustomerWebpage $customerWebpage */
        $customerWebpage = $this;


        return [
            'slug'  => $customerWebpage->slug,
            'title' => $customerWebpage->title,
            'url'   => $customerWebpage->url,
        ];
    }
}
