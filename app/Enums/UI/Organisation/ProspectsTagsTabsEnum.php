<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 03:22:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ProspectsTagsTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;


    case TAGS     = 'tags';

    case HISTORY   = 'history';

    public function blueprint(): array
    {
        return match ($this) {
            ProspectsTagsTabsEnum::TAGS => [
                'title' => __('tags'),
                'icon'  => 'fal fa-tags',
            ],
            ProspectsTagsTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
