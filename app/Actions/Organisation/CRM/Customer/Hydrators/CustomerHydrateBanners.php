<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Customer\Hydrators;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\CRM\Customer;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateBanners implements ShouldBeUnique
{
    use AsAction;


    public function handle(Customer $customer): void
    {
        $stats = [
            'number_banners'            => $customer->banners()->count(),
            'number_historic_snapshots' => $customer->snapshots()->where('state', SnapshotStateEnum::HISTORIC)->count()
        ];

        foreach (BannerStateEnum::cases() as $state) {
            $stats['number_banners_state_'.$state->snake()] = $customer->banners()->where('state', $state->value)->count();
        }
        $customer->portfolioStats->update($stats);
    }

}
