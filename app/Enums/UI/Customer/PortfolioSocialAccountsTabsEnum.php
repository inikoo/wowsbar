<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum PortfolioSocialAccountsTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case ACCOUNTS             = 'accounts';
    case CHANGELOG            = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            PortfolioSocialAccountsTabsEnum::ACCOUNTS => [
                'title' => __('accounts'),
                'icon'  => 'fal fa-bars',
            ],

            PortfolioSocialAccountsTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
