<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 15:56:34 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum CustomersNewslettersTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case NEWSLETTERS = 'newsletters';

    case HISTORY     = 'history';
    case UNSUBSCRIBE = 'unsubscribe';


    public function blueprint(): array
    {
        return match ($this) {

            CustomersNewslettersTabsEnum::NEWSLETTERS => [
                'title' => __('newsletters'),
                'icon'  => 'fal fa-bars',
            ],

            CustomersNewslettersTabsEnum::UNSUBSCRIBE => [
                'title' => __('unsubscribe'),
                'icon'  => 'fal fa-text',
                'type'  => 'icon',
                'align' => 'right'
            ],

            CustomersNewslettersTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
