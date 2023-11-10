<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum EmailTemplateTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE              = 'showcase';
    case SNAPSHOTS             = 'snapshots';


    case CHANGELOG            = 'changelog';



    public function blueprint(): array
    {
        return match ($this) {
            EmailTemplateTabsEnum::SHOWCASE => [
                'title' => __('email template'),
                'icon'  => 'fas fa-info-circle',
            ],
            EmailTemplateTabsEnum::SNAPSHOTS => [
                'title' => __('snapshots'),
                'icon'  => 'fal fa-layer-group',
            ],

            EmailTemplateTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
