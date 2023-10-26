<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum TaskTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case ACTIVITIES             = 'activities';
    case CHANGELOG              = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            TaskTabsEnum::ACTIVITIES => [
                'title' => __('activities'),
                'icon'  => 'fal fa-bars',
            ],

            TaskTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
