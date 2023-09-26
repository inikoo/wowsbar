<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\Marketing\Shop\ShopStateEnum;
use App\Enums\Marketing\Shop\ShopTypeEnum;
use App\Models\Market\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateShops
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_shops' => Shop::count()
        ];

        array_merge($stats, $this->getEnumStats('shops', 'state', ShopStateEnum::class, Shop::class));
        array_merge($stats, $this->getEnumStats('shops', 'type', ShopTypeEnum::class, Shop::class));


        organisation()->stats()->update($stats);
    }
}
