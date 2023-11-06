<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 16:58:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ShowProspectTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE = 'showcase';
    case PROSPECTS = 'prospects';

    public function blueprint(): array
    {
        return match ($this) {
            ShowProspectTabsEnum::SHOWCASE => [
                'title' => __('dashboard'),
                'icon'  => 'fal fa-info',
            ],

            ShowProspectTabsEnum::PROSPECTS => [
                'title' => __('prospects'),
                'icon'  => 'fal fa-transporter',
            ],
        };
    }
}
