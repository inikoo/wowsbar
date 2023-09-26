<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Models\Market\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateCustomerWebsites
{
    use AsAction;
    use WithEnumStats;

    public function handle(Shop $shop): void
    {
        $stats = [
            'number_customer_websites' => $shop->customerWebsites()->count()
        ];
        $shop->crmStats()->update($stats);
    }
}
