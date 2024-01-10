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

    case WEBSITES                = 'websites';
    case SOCIAL_ACCOUNT          = 'social_account';
    case ORDERS                  = 'orders';
    case SHIPPER_ACCOUNTS        = 'shipper_accounts';
    case SHIPMENTS               = 'shipments';


    case DATA               = 'data';
    case ATTACHMENTS        = 'attachments';
    case USERS              = 'users';

    case DISPATCHED_EMAILS = 'dispatched_emails';
    case APPOINTMENTS      = 'appointments';

    public function blueprint(): array
    {
        return match ($this) {

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
            CustomerTabsEnum::WEBSITES => [
                'title' => __('portfolio websites'),
                'icon'  => 'fal fa-briefcase',
            ],
            CustomerTabsEnum::SOCIAL_ACCOUNT => [
                'title' => __('social accounts'),
                'icon'  => 'fal fa-microphone-stand',
            ],

            CustomerTabsEnum::ORDERS => [
                'title' => __('orders'),
                'icon'  => 'fal fa-shopping-cart',
            ],
            CustomerTabsEnum::SHIPPER_ACCOUNTS => [
                'title' => __('shipper accounts'),
                'icon'  => 'fal fa-shipping-fast',
            ],
            CustomerTabsEnum::SHIPMENTS => [
                'title' => __('shipments'),
                'icon'  => 'fal fa-shipping-fast',
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
            CustomerTabsEnum::APPOINTMENTS => [
                'align' => 'right',
                'title' => __('appointments'),
                'icon'  => 'fal fa-handshake',
                'type'  => 'icon',
            ],
            CustomerTabsEnum::SHOWCASE => [
                'title' => __('customer'),
                'icon'  => 'fas fa-info-circle',
            ],
        };
    }
}
