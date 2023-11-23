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
            'number_prospects'                 => Prospect::where('shop_id', $shop->id)->count(),
            'number_prospects_dont_contact_me' => Prospect::where('shop_id', $shop->id)->where('dont_contact_me', true)->count(),
        ];



        $stats=array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'state',
                enum: ProspectStateEnum::class,
                models: Prospect::class,
                where: function ($q) use ($shop) {
                    $q->where('shop_id', $shop->id);
                }
            )
        );



        $shop->crmStats()->update($stats);
    }
}
