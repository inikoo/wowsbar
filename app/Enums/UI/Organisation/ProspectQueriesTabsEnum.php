<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 15:38:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ProspectQueriesTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;


    case LISTS     = 'lists';
    case HISTORY   = 'history';


    public function blueprint(): array
    {
        return match ($this) {



            ProspectQueriesTabsEnum::LISTS => [
                'title' => __('lists'),
                'icon'  => 'fal fa-code-branch',
            ],



            ProspectQueriesTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
