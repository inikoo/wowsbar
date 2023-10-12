<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 12 Oct 2023 08:51:26 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum CustomerUsersTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case USERS                       = 'users';
    case USERS_HISTORIES             = 'history';

    case USERS_REQUESTS              = 'users_requests';


    public function blueprint(): array
    {
        return match ($this) {
            CustomerUsersTabsEnum::USERS => [
                'title' => __('users'),
                'icon'  => 'fal fa-terminal',
            ],
            CustomerUsersTabsEnum::USERS_HISTORIES => [
                'title' => __("user's history"),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ],
            CustomerUsersTabsEnum::USERS_REQUESTS => [
                'title' => __('users requests'),
                'icon'  => 'fal fa-road',
                'type'  => 'icon',
                'align' => 'right'
            ],

        };
    }
}
