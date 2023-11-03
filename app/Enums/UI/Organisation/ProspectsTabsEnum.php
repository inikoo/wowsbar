<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 16:58:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ProspectsTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case PROSPECTS = 'prospects';
    case SHOWCASE = 'showcase';

    case HISTORY   = 'history';

    case MAILSHOTS = 'mailshots';

    public function blueprint(): array
    {
        return match ($this) {
            ProspectsTabsEnum::PROSPECTS => [
                'title' => __('prospects'),
                'icon'  => 'fal fa-transporter',
            ],
            ProspectsTabsEnum::SHOWCASE => [
                'title' => __('Dashboard'),
                'icon'  => 'fal fa-transporter',
            ],

            ProspectsTabsEnum::MAILSHOTS => [
                'title' => __('mailshots'),
                'icon'  => 'fal fa-mail-bulk',
                'type'  => 'icon',
                'align' => 'right'
            ],

            ProspectsTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
