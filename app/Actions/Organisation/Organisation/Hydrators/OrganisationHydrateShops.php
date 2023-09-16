<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Enums\Market\Shop\ShopStateEnum;
use App\Enums\Market\Shop\ShopTypeEnum;
use App\Models\Organisation\Market\Shop;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateShops
{
    use AsAction;

    public function handle(): void
    {

        $stats = [
            'number_shops' => Shop::count()
        ];

        $shopStateCount = Shop::selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();
        foreach (ShopStateEnum::cases() as $shopState) {
            $stats['number_shops_state_'.$shopState->snake()] = Arr::get($shopStateCount, $shopState->value, 0);
        }

        $shopTypeCount = Shop::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')->all();
        foreach (ShopTypeEnum::cases() as $shopType) {
            $stats['number_shops_type_'.$shopType->snake()] = Arr::get($shopTypeCount, $shopType->value, 0);
        }


        organisation()->stats()->update($stats);
    }
}
