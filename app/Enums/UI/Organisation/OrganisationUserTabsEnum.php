<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Mon, 20 Mar 2023 14:46:07 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum OrganisationUserTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE                       = 'showcase';
    case HISTORY                        = 'history';
    case REQUEST_LOGS                   = 'request_logs';

    case DATA                           = 'data';


    public function blueprint(): array
    {
        return match ($this) {

            OrganisationUserTabsEnum::DATA => [
                'title' => __('database'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            OrganisationUserTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
            OrganisationUserTabsEnum::SHOWCASE => [
                'title' => __('user'),
                'icon'  => 'fas fa-info-circle',
            ],
            OrganisationUserTabsEnum::REQUEST_LOGS => [
                'title' => __('visit logs'),
                'icon'  => 'fal fa-road',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
