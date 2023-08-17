<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Tenant;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum PortfolioWebsiteTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE             = 'showcase';

    case BANNERS             = 'banners';

    case CHANGELOG            = 'changelog';

    case DATA                 = 'data';


    public function blueprint(): array
    {
        return match ($this) {
            PortfolioWebsiteTabsEnum::SHOWCASE => [
                'title' => __('website'),
                'icon'  => 'fas fa-info-circle',
            ],
            PortfolioWebsiteTabsEnum::BANNERS => [
                'title' => __('banners'),
                'icon'  => 'fal fa-window-maximize',
            ],
            PortfolioWebsiteTabsEnum::DATA => [
                'title' => __('data'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            PortfolioWebsiteTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
