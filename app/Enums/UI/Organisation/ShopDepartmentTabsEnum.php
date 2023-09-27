<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 12:11:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ShopDepartmentTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;


    case SHOWCASE            = 'showcase';
    case PRODUCTS            = 'products';
    case SALES               = 'sales';
    case CUSTOMERS           = 'customers';

    case HISTORY             = 'history';

    case DATA                = 'data';



    public function blueprint(): array
    {
        return match ($this) {
            ShopDepartmentTabsEnum::DATA => [
                'title' => __('database'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            ShopDepartmentTabsEnum::PRODUCTS => [
                'title' => __('products'),
                'icon'  => 'fal fa-cube',
            ],

            ShopDepartmentTabsEnum::SALES => [
                'title' => __('sales'),
                'icon'  => 'fal fa-money-bill-wave',
            ],
            ShopDepartmentTabsEnum::CUSTOMERS => [
                'title' => __('customers'),
                'icon'  => 'fal fa-user',
            ],ShopDepartmentTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
            ShopDepartmentTabsEnum::SHOWCASE => [
                'title' => __('department'),
                'icon'  => 'fas fa-info-circle',
            ],
        };
    }
}
