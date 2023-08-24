<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators;

use App\Actions\Tenancy\Tenant\Hydrators\HasTenantHydrate;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateBanners implements ShouldBeUnique
{
    use AsAction;
    use HasTenantHydrate;


    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        $stats = [
            'number_banners' => $portfolioWebsite->banners()->count()
        ];


        foreach (BannerStateEnum::cases() as $state) {
            $stats['number_banners_state_'.$state->snake()] = $portfolioWebsite->banners()->where('state', $state->value)->count();
        }

        $portfolioWebsite->stats->update($stats);
    }
}
