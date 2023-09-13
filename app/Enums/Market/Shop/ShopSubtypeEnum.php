<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 13:33:09 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Market\Shop;

use App\Enums\EnumHelperTrait;

enum ShopSubtypeEnum: string
{
    use EnumHelperTrait;

    case MARKETING = 'marketing';


    public static function labels(): array
    {
        return [
            'marketing'    => 'Marketing'

        ];
    }
}
