<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 10:30:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum TasksTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case TASKS                  = 'tasks';
    case CHANGELOG              = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            TasksTabsEnum::TASKS => [
                'title' => __('tasks'),
                'icon'  => 'fal fa-bars',
            ],

            TasksTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
