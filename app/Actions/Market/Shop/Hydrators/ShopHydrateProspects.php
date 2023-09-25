<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateProspects
{
    use AsAction;
    use WithEnumStats;

    public function handle(Shop $shop): void
    {
        $stats = [
            'number_prospects' => Prospect::where('shop_id', $shop->id)->count()
        ];

        array_merge($stats, $this->getEnumStats(
            'prospects',
            'state',
            ProspectStateEnum::class,
            Prospect::where('shop_id', $shop->id)
        ));
        organisation()->crmStats()->update($stats);
    }
}
