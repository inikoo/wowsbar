<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 08 Jun 2023 23:19:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum WorkingPlaceTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case HQ              = 'hq';
    case BRANCH          = 'branch';
    case HOME            = 'home';
    case GROUP_PREMISSES = 'group-premisses';
    case CLIENT_PREMISES = 'client-premises';
    case ROAD            = 'road';
    case OTHER           = 'other';

    public function blueprint(): array
    {
        return match ($this) {
            WorkingPlaceTabsEnum::HQ => [
                'title' => __('color scheme'),
                'icon'  => 'fal fa-palette',
            ],
            WorkingPlaceTabsEnum::HOME => [
                'title' => __('header'),
                'icon'  => 'fal fa-arrow-alt-to-top',
            ],
            WorkingPlaceTabsEnum::BRANCH => [
                'title' => __('menu'),
                'icon'  => 'fal fa-bars',
            ],
            WorkingPlaceTabsEnum::CLIENT_PREMISES => [
                'title' => __('footer'),
                'icon'  => 'fal fa-arrow-alt-to-bottom',
            ],
            WorkingPlaceTabsEnum::GROUP_PREMISSES => [
                'title' => __('footer'),
                'icon'  => 'fal fa-arrow-alt-to-left',
            ],
            WorkingPlaceTabsEnum::ROAD => [
                'title' => __('footer'),
                'icon'  => 'fal fa-street',
            ],
            WorkingPlaceTabsEnum::OTHER => [
                'title' => __('footer'),
                'icon'  => 'fal fa-arrow-alt-to-up',
            ],

        };
    }

}
