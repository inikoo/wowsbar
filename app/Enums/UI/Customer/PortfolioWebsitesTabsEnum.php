<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum PortfolioWebsitesTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case WEBSITES             = 'websites';
    case UPLOADED_WEBSITES    = 'uploaded_websites';
    case CHANGELOG            = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            PortfolioWebsitesTabsEnum::WEBSITES => [
                'title' => __('websites'),
                'icon'  => 'fas fa-info-circle',
            ],
            PortfolioWebsitesTabsEnum::UPLOADED_WEBSITES => [
                'title' => __('uploaded websites'),
                'icon'  => 'fal fa-window-maximize',
            ],
            PortfolioWebsitesTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
