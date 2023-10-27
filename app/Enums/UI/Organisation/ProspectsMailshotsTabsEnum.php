<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 15:56:34 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ProspectsMailshotsTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case MAILSHOTS = 'mailshots';

    case HISTORY   = 'history';


    public function blueprint(): array
    {
        return match ($this) {


            ProspectsMailshotsTabsEnum::MAILSHOTS => [
                'title' => __('mailshots'),
                'icon'  => 'fal fa-bulk-mail',
            ],

            ProspectsMailshotsTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
