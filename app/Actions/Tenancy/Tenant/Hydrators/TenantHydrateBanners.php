<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant\Hydrators;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\Tenancy\Tenant;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class TenantHydrateBanners implements ShouldBeUnique
{
    use AsAction;
    use HasTenantHydrate;


    public function handle(Tenant $tenant): void
    {
        $stats = [
            'number_banners'            => $tenant->banners()->count(),
            'number_historic_snapshots' => $tenant->snapshots()->where('state', SnapshotStateEnum::HISTORIC)->count()
        ];

        foreach (BannerStateEnum::cases() as $state) {
            $stats['number_banners_state_'.$state->snake()] = $tenant->banners()->where('state', $state->value)->count();
        }
        $tenant->portfolioStats->update($stats);
    }

}
