<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Mon, 20 Mar 2023 14:46:07 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum WebsiteWorkshopTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case LAYOUT               = 'workshop_layout';
    case HEADER               = 'workshop_header';
    case FOOTER               = 'workshop_footer';




    public function blueprint(): array
    {
        return match ($this) {
            WebsiteWorkshopTabsEnum::LAYOUT => [
                'title' => __('layout'),
                'icon'  => 'fal fa-layer-group',
            ],
            WebsiteWorkshopTabsEnum::HEADER => [
                'title' => __('header'),
                'icon'  => 'fal fa-arrow-alt-to-top',
            ],
            WebsiteWorkshopTabsEnum::FOOTER => [
                'title' => __('footer'),
                'icon'  => 'fal fa-arrow-alt-to-bottom',
            ],

        };
    }
}
