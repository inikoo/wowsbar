<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\PortfolioSocialAccount;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class PortfolioSocialAccountResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var PortfolioSocialAccount $socialAccount */
        $socialAccount = $this;

        return [
            'slug'             => $socialAccount->slug,
            'username'         => $socialAccount->username,
            'platform'         => $socialAccount->platform,
            'url'              => $socialAccount->url,
            'number_posts'     => $socialAccount->number_posts,
            'number_followers' => $socialAccount->number_followers,
            'platform_icon'    => $socialAccount->platform->platformIcon()[$socialAccount->platform->value]
        ];
    }
}
