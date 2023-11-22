<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 22 Nov 2023 23:33:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum CustomersTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case DASHBOARD = 'dashboard';
    case CUSTOMERS = 'customers';

    case HISTORY   = 'history';
    case TAGS      = 'tags';
    case LISTS     = 'lists';
    case MAILSHOTS = 'mailshots';

    case NEWSLETTERS = 'newsletters';

    public function blueprint(): array
    {
        return match ($this) {
            CustomersTabsEnum::DASHBOARD => [
                'title' => __('dashboard'),
                'icon'  => 'fal fa-tachometer-alt',
            ],

            CustomersTabsEnum::CUSTOMERS => [
                'title' => __('customers'),
                'icon'  => 'fal fa-user',
            ],

            CustomersTabsEnum::LISTS => [
                'title' => __('lists'),
                'icon'  => 'fal fa-code-branch',
                'type'  => 'icon',
                'align' => 'right'
            ],

            CustomersTabsEnum::TAGS => [
                'title' => __('tags'),
                'icon'  => 'fal fa-tags',
                'type'  => 'icon',
                'align' => 'right'
            ],

            CustomersTabsEnum::MAILSHOTS => [
                'title' => __('mailshots'),
                'icon'  => 'fal fa-mail-bulk',
                'type'  => 'icon',
                'align' => 'right'
            ],

            CustomersTabsEnum::NEWSLETTERS => [
                'title' => __('newsletters'),
                'icon'  => 'fal fa-newspaper',
                'type'  => 'icon',
                'align' => 'right'
            ],

            CustomersTabsEnum::HISTORY => [
                'title' => __('history'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right'
            ]
        };
    }
}
