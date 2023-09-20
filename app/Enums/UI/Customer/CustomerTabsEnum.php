<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 11:43:42 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum CustomerTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE = 'showcase';

    case TIMELINE  = 'timeline';
    case PORTFOLIO = 'portfolio';
    case ORDERS    = 'orders';


    case DATA               = 'data';
    case ATTACHMENTS        = 'attachments';
    case USERS              = 'users';

    case DISPATCHED_EMAILS = 'dispatched_emails';

    public function blueprint(): array
    {
        return match ($this) {
            CustomerTabsEnum::TIMELINE => [
                'title' => __('timeline'),
                'icon'  => 'fal fa-code-branch',
            ],
            CustomerTabsEnum::DATA => [
                'align' => 'right',
                'type'  => 'icon',
                'title' => __('data'),
                'icon'  => 'fal fa-database',
            ],
            CustomerTabsEnum::USERS => [
                'align' => 'right',
                'type'  => 'icon',
                'title' => __('users'),
                'icon'  => 'fal fa-terminal',
            ],
            CustomerTabsEnum::PORTFOLIO => [
                'title' => __('portfolio'),
                'icon'  => 'fal fa-store-alt',
            ],

            CustomerTabsEnum::ORDERS => [
                'title' => __('orders'),
                'icon'  => 'fal fa-shopping-cart',
            ],
            CustomerTabsEnum::ATTACHMENTS => [
                'align' => 'right',
                'title' => __('attachments'),
                'icon'  => 'fal fa-paperclip',
                'type'  => 'icon'
            ],
            CustomerTabsEnum::DISPATCHED_EMAILS => [
                'align' => 'right',
                'title' => __('dispatched emails'),
                'icon'  => 'fal fa-paper-plane',
                'type'  => 'icon',
            ],
            CustomerTabsEnum::SHOWCASE => [
                'title' => __('customer'),
                'icon'  => 'fas fa-info-circle',
            ],
        };
    }
}
