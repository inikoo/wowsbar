<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 14:29:45 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum GalleryTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case UPLOADED_IMAGES             = 'uploaded_images';

    case STOCK_IMAGES             = 'stock_images';





    public function blueprint(): array
    {
        return match ($this) {
            GalleryTabsEnum::UPLOADED_IMAGES => [
                'title' => __('uploaded images'),
                'icon'  => 'fal fa-cloud-upload',
            ],
            GalleryTabsEnum::STOCK_IMAGES => [
                'title' => __('stock images'),
                'icon'  => 'fal fa-image-polaroid',
            ],


        };
    }
}
