<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum PortfolioSocialAccountTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case ACCOUNTS             = 'accounts';
    case CHANGELOG            = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            PortfolioSocialAccountTabsEnum::ACCOUNTS => [
                'title' => __('accounts'),
                'icon'  => 'fas fa-info-circle',
            ],

            PortfolioSocialAccountTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
