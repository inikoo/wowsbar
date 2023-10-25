<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum TaskActivityTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case ACTIVITIES             = 'activities';
    case CHANGELOG            = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            TaskActivityTabsEnum::ACTIVITIES => [
                'title' => __('accounts'),
                'icon'  => 'fal fa-bars',
            ],

            TaskActivityTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
