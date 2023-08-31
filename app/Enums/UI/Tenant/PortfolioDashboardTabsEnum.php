<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 31 Aug 2023 10:07:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Tenant;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum PortfolioDashboardTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case DASHBOARD              = 'dashboard';


    case PORTFOLIO_CHANGELOG            = 'history';



    public function blueprint(): array
    {
        return match ($this) {
            PortfolioDashboardTabsEnum::DASHBOARD => [
                'title' => __('dashboard'),
                'icon'  => 'fas fa-tachometer-alt',
            ],

            PortfolioDashboardTabsEnum::PORTFOLIO_CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
