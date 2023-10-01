<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 03:11:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum BannersDashboardTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case DASHBOARD              = 'dashboard';


    case PORTFOLIO_CHANGELOG            = 'history';



    public function blueprint(): array
    {
        return match ($this) {
            BannersDashboardTabsEnum::DASHBOARD => [
                'title' => __('dashboard'),
                'icon'  => 'fas fa-tachometer-alt',
            ],

            BannersDashboardTabsEnum::PORTFOLIO_CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
