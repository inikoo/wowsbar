<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\CRM\Customer;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateSnapshot implements ShouldBeUnique
{
    use AsAction;


    public function handle(Customer $customer): void
    {
        $stats = [
            'number_snapshots' => $customer->snapshots()->count()
        ];

        foreach (SnapshotStateEnum::cases() as $state) {
            $stats['number_snapshots_state_'.$state->snake()] = $customer->banners()->where('state', $state->value)->count();
        }

        $customer->portfolioStats()->update($stats);
    }

}
