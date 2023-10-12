<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Mon, 20 Mar 2023 14:46:07 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum OrganisationUsersTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case USERS                       = 'users';
    case USERS_HISTORIES             = 'history';

    case USERS_REQUESTS              = 'users_requests';

    public function blueprint(): array
    {
        return match ($this) {
            OrganisationUsersTabsEnum::USERS => [
                'title' => __('users'),
                'icon'  => 'fal fa-terminal',
            ],
            OrganisationUsersTabsEnum::USERS_REQUESTS => [
                'title' => __('visit logs'),
                'icon'  => 'fal fa-road',
                'type'  => 'icon',
                'align' => 'right'
            ],
            OrganisationUsersTabsEnum::USERS_HISTORIES => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
