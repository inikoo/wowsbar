<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Mon, 20 Mar 2023 14:46:07 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum EmployeeTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE                       = 'showcase';
    case HISTORY                        = 'history';
    case DATA                           = 'data';
    case TODAY_TIMESHEETS               = 'today_timesheets';
    case TIMESHEETS                     = 'timesheets';
    case ATTACHMENTS                    = 'attachments';
    case IMAGES                         = 'images';


    public function blueprint(): array
    {
        return match ($this) {
            EmployeeTabsEnum::TODAY_TIMESHEETS => [
                'title' => __('today time sheets'),
                'icon'  => 'fal fa-database',
            ],
            EmployeeTabsEnum::IMAGES => [
                'title' => __('images'),
                'icon'  => 'fal fa-camera-retro',
                'type'  => 'icon',
                'align' => 'right',
            ],
            EmployeeTabsEnum::ATTACHMENTS => [
                'title' => __('attachments'),
                'icon'  => 'fal fa-paperclip',
                'type'  => 'icon',
                'align' => 'right',
            ],
            EmployeeTabsEnum::TIMESHEETS => [
                'title' => __('time sheets'),
                'icon'  => 'fal fa-database',
            ],
            EmployeeTabsEnum::DATA => [
                'title' => __('database'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            EmployeeTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
            EmployeeTabsEnum::SHOWCASE => [
                'title' => __('employee'),
                'icon'  => 'fas fa-info-circle',
            ],
        };
    }
}
