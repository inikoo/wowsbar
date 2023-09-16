<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 27 Apr 2023 15:47:35 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Market\Product;

use App\Enums\EnumHelperTrait;

enum ProductTypeEnum: string
{
    use EnumHelperTrait;

    case SUBSCRIPTION = 'subscription';
    case SERVICE      = 'service';


    public static function labels(): array
    {
        return [
            'subscription' => __('Subscription'),
            'service'      => __('Service'),
        ];
    }

}
