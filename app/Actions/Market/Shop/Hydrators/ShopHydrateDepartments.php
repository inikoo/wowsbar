<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Enums\Market\ProductCategory\ProductCategoryStateEnum;
use App\Models\Market\ProductCategoryShop;
use App\Models\Market\Shop;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateDepartments implements ShouldBeUnique
{
    use AsAction;


    public function handle(Shop $shop): void
    {
        $stateCounts      = ProductCategoryShop::where('shop_id', $shop->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();
        $stats            = [
            'number_departments' => $shop->departments->count(),
        ];
        foreach (ProductCategoryStateEnum::cases() as $departmentState) {
            $stats['number_departments_state_'.$departmentState->snake()] = Arr::get($stateCounts, $departmentState->value, 0);
        }
        $shop->stats->update($stats);
    }

    public function getJobUniqueId(Shop $shop): string
    {
        return $shop->id;
    }
}
