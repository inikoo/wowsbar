<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 20:25:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum BannersPortfolioWebsiteTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;


    case BANNERS                = 'banners';

    case CHANGELOG            = 'changelog';


    public function blueprint(): array
    {
        return match ($this) {

            BannersPortfolioWebsiteTabsEnum::BANNERS => [
                'title' => __('banners'),
                'icon'  => 'fal fa-sign',
            ],
            BannersPortfolioWebsiteTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
