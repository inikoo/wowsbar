<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum PortfolioWebsiteTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE             = 'showcase';

    case BANNERS                = 'banners';
    case SEO                    = 'seo';
    case GOOGLE_ADS             = 'google_ads';
    case LEADS                  = 'leads';

    case CHANGELOG            = 'changelog';


    public function blueprint(): array
    {
        return match ($this) {
            PortfolioWebsiteTabsEnum::SHOWCASE => [
                'title' => __('website'),
                'icon'  => 'fas fa-info-circle',
            ],
            PortfolioWebsiteTabsEnum::BANNERS => [
                'title' => __('banners'),
                'icon'  => 'fal fa-sign',
            ],
            PortfolioWebsiteTabsEnum::LEADS => [
                'title' => __('leads'),
                'icon'  => 'fal fa-transporter',
            ],
            PortfolioWebsiteTabsEnum::SEO => [
                'title' => __('SEO'),
                'icon'  => 'fab fa-google',
            ],
            PortfolioWebsiteTabsEnum::GOOGLE_ADS => [
                'title' => __('google Ads'),
                'icon'  => 'fal fa-bullseye',
            ],

            PortfolioWebsiteTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
