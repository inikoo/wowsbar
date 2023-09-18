<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\Hydrators;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateBanners implements ShouldBeUnique
{
    use AsAction;


    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        $stats = [
            'number_banners' => $portfolioWebsite->banners()->count(),
        ];

        foreach (BannerStateEnum::cases() as $state) {
            $stats['number_banners_state_'.$state->snake()] = $portfolioWebsite->banners()->where('state', $state->value)->count();
        }

        $portfolioWebsite->stats->update($stats);
    }
}
