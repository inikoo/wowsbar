<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 04 Oct 2023 08:09:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum StockImageTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE = 'showcase';
    case BANNERS  = 'banners';
    case DATA     = 'data';


    public function blueprint(): array
    {
        return match ($this) {
            StockImageTabsEnum::SHOWCASE => [
                'title' => __('stock image'),
                'icon'  => 'fas fa-info-circle',
            ],
            StockImageTabsEnum::BANNERS => [
                'title' => __('banners'),
                'icon'  => 'fal fa-rectangle-wide',
            ],
            StockImageTabsEnum::DATA => [
                'title' => __('data'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],

        };
    }
}
