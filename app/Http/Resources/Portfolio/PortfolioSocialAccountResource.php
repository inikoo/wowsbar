<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class PortfolioSocialAccountResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Portfolio\PortfolioSocialAccount $socialAccount */
        $socialAccount = $this;

        return [
            'username'         => $socialAccount->username,
            'provider'         => $socialAccount->provider,
            'url'              => $socialAccount->url,
            'number_posts'     => $socialAccount->number_posts,
            'number_followers' => $socialAccount->number_followers
        ];
    }
}
