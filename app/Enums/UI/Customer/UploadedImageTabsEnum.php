<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 04 Oct 2023 08:09:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum UploadedImageTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE  = 'showcase';
    case BANNERS   = 'banners';
    case CHANGELOG = 'changelog';
    case DATA      = 'data';


    public function blueprint(): array
    {
        return match ($this) {
            UploadedImageTabsEnum::SHOWCASE => [
                'title' => __('stock image'),
                'icon'  => 'fas fa-info-circle',
            ],
            UploadedImageTabsEnum::BANNERS => [
                'title' => __('banners'),
                'icon'  => 'fal fa-sign',
            ],
            UploadedImageTabsEnum::DATA => [
                'title' => __('data'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            UploadedImageTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
