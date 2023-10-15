<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum CustomerWebsiteTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE             = 'showcase';

    case BANNERS                = 'banners';
    case SEO                    = 'seo';
    case GOOGLE_ADS             = 'google_ads';
    case LEADS                  = 'leads';

    case CHANGELOG            = 'changelog';

    case DATA                 = 'data';

    public function blueprint(): array
    {
        return match ($this) {
            CustomerWebsiteTabsEnum::SHOWCASE => [
                'title' => __('website'),
                'icon'  => 'fas fa-info-circle',
            ],
            CustomerWebsiteTabsEnum::BANNERS => [
                'title' => __('banners'),
                'icon'  => 'fal fa-sign',
            ],
            CustomerWebsiteTabsEnum::LEADS => [
                'title' => __('leads'),
                'icon'  => 'fal fa-transporter',
            ],
            CustomerWebsiteTabsEnum::SEO => [
                'title' => __('SEO'),
                'icon'  => 'fab fa-google',
            ],
            CustomerWebsiteTabsEnum::GOOGLE_ADS => [
                'title' => __('google Ads'),
                'icon'  => 'fal fa-bullseye',
            ],
            CustomerWebsiteTabsEnum::DATA => [
                'title' => __('data'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            CustomerWebsiteTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
