<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 09:38:55 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ProspectsDashboardTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case DASHBOARD              = 'dashboard';


    case CHANGELOG            = 'history';



    public function blueprint(): array
    {
        return match ($this) {
            ProspectsDashboardTabsEnum::DASHBOARD => [
                'title' => __('dashboard'),
                'icon'  => 'fas fa-tachometer-alt',
            ],

            ProspectsDashboardTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
