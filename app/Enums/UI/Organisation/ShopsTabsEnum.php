<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 16 Sep 2023 11:42:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ShopsTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOPS                       = 'shops';
    case DEPARTMENTS                 = 'departments';
    case PRODUCTS                    = 'products';

    public function blueprint(): array
    {
        return match ($this) {
            ShopsTabsEnum::SHOPS => [
                'title' => __('shops'),
                'icon'  => 'fal fa-store-alt',
            ],
            ShopsTabsEnum::DEPARTMENTS => [
                'title' => __('departments'),
                'icon'  => 'fal fa-folder-tree',
            ],
            ShopsTabsEnum::PRODUCTS => [
                'title' => __('products'),
                'icon'  => 'fal fa-cube',
            ],

        };
    }
}
