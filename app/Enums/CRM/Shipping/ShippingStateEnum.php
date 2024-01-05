<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 21 Jun 2023 08:44:31 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Shipping;

use App\Enums\EnumHelperTrait;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;

enum ShippingStateEnum: string
{
    use EnumHelperTrait;

    case PENDING  = 'pending';
    case ERROR    = 'contacted';
    case FAIL     = 'fail';
    case SUCCESS  = 'success';

    public static function labels(): array
    {
        return [
            'pending' => __('Pending'),
            'error'    => __('Error'),
            'fail'         => __('Fail'),
            'success'      => __('Success')
        ];
    }

    public static function stateIcon(): array
    {
        return [
            'pending' => [
                'tooltip' => __('pending'),
                'icon'    => 'fal fa-seedling',
                'class'   => 'text-indigo-500'
            ],
            'error'    => [
                'tooltip' => __('error'),
                'icon'    => 'fal fa-chair',
            ],
            'fail'         => [
                'tooltip' => __('fail'),
                'icon'    => 'fal fa-thumbs-down',
                'class'   => 'text-red'
            ],
            'success'      => [
                'tooltip' => __('success'),
                'icon'    => 'fal fa-laugh'
            ],
        ];
    }
}
