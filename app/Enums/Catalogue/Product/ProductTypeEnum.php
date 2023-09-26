<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 09:36:52 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Catalogue\Product;

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
