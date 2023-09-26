<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum CustomerWebsitesTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case WEBSITES             = 'websites';
    case CHANGELOG            = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            CustomerWebsitesTabsEnum::WEBSITES => [
                'title' => __('websites'),
              //  'icon'  => 'fas fa-info-circle',
            ],
            CustomerWebsitesTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
