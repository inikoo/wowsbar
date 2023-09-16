<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 16 Sep 2023 11:42:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ShopTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE    = 'showcase';
    case DASHBOARD   = 'dashboard';
    case DEPARTMENTS = 'departments';

    case PRODUCTS = 'products';
    case HISTORY  = 'history';
    case DATA     = 'data';

    public function blueprint(): array
    {
        return match ($this) {
            ShopTabsEnum::DASHBOARD => [
                'title' => __('stats'),
                'icon'  => 'fal fa-chart-line',
            ],
            ShopTabsEnum::DEPARTMENTS => [
                'title' => __('departments'),
                'icon'  => 'fal fa-folder-tree',
            ],
            ShopTabsEnum::PRODUCTS => [
                'title' => __('products'),
                'icon'  => 'fal fa-cube',
            ],
            ShopTabsEnum::DATA => [
                'align' => 'right',
                'type'  => 'icon',
                'title' => __('data'),
                'icon'  => 'fal fa-database',
            ],
            ShopTabsEnum::HISTORY => [
                'align' => 'right',
                'type'  => 'icon',
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
            ],
            ShopTabsEnum::SHOWCASE => [
                'title' => __('shop'),
                'icon'  => 'fas fa-info-circle',
            ],
        };
    }
}
