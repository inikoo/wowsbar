<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Tenant;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum BannerTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE              = 'showcase';
    case SNAPSHOTS             = 'snapshots';


    case CHANGELOG            = 'changelog';

    case DATA                 = 'data';


    public function blueprint(): array
    {
        return match ($this) {
            BannerTabsEnum::SHOWCASE => [
                'title' => __('banner'),
                'icon'  => 'fas fa-info-circle',
            ],
            BannerTabsEnum::SNAPSHOTS => [
                'title' => __('snapshots'),
                'icon'  => 'fal fa-wallet',
            ],
            BannerTabsEnum::DATA => [
                'title' => __('data'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            BannerTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
