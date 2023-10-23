<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 10:02:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum BannersTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case BANNERS             = 'banners';
    case CHANGELOG            = 'changelog';

    public function blueprint(): array
    {
        return match ($this) {
            BannersTabsEnum::BANNERS => [
                'title' => __('banners'),
            ],
            BannersTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
