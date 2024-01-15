<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail\Outbox;

use App\Enums\EnumHelperTrait;
use App\Enums\Mail\Mailroom\MailroomCodeEnum;

enum OutboxTypeEnum: string
{
    use EnumHelperTrait;


    case NEW_CUSTOMER       = 'new_customer';
    case INVOICE_DELETED    = 'invoice_deleted';
    case NEW_ORDER          = 'new_order';
    case SHOP_PROSPECT      = 'shop-prospect';
    case CUSTOMER_PROSPECT  = 'customer-prospect';
    case MARKETING          = 'marketing';
    case NEWSLETTER         = 'newsletter';
    case ORDER_CONFIRMATION = 'order_confirmation';
    case PASSWORD_REMINDER  = 'password_reminder';
    case REGISTRATION       = 'registration';

    case TEST = 'test';


    public function label(): string
    {
        return match ($this) {
            OutboxTypeEnum::NEW_CUSTOMER       => __('New customer'),
            OutboxTypeEnum::INVOICE_DELETED    => __('Invoice deleted'),
            OutboxTypeEnum::NEW_ORDER          => __('New order'),
            OutboxTypeEnum::SHOP_PROSPECT      => __('Shop prospect'),
            OutboxTypeEnum::CUSTOMER_PROSPECT  => __('Customer prospect'),
            OutboxTypeEnum::MARKETING          => __('Marketing'),
            OutboxTypeEnum::NEWSLETTER         => __('Newsletter'),
            OutboxTypeEnum::ORDER_CONFIRMATION => __('Order confirmation'),
            OutboxTypeEnum::PASSWORD_REMINDER  => __('Password reminder'),
            OutboxTypeEnum::REGISTRATION       => __('Registration'),
            OutboxTypeEnum::TEST               => __('Test'),
        };
    }

    public function scope(): string
    {
        return match ($this) {
            OutboxTypeEnum::TEST => 'organisation',
            default              => 'shop'
        };
    }

    public function defaultState(): OutboxStateEnum
    {
        return match ($this) {
            OutboxTypeEnum::MARKETING,
            OutboxTypeEnum::NEWSLETTER,
            OutboxTypeEnum::SHOP_PROSPECT,
            OutboxTypeEnum::CUSTOMER_PROSPECT,
            OutboxTypeEnum::TEST,
            => OutboxStateEnum::ACTIVE,
            default => OutboxStateEnum::IN_PROCESS
        };
    }


    public function mailroomCode(): MailroomCodeEnum
    {
        return match ($this) {
            OutboxTypeEnum::REGISTRATION,
            OutboxTypeEnum::PASSWORD_REMINDER,
            OutboxTypeEnum::ORDER_CONFIRMATION
            => MailroomCodeEnum::CUSTOMER_NOTIFICATION,
            OutboxTypeEnum::INVOICE_DELETED,
            OutboxTypeEnum::NEW_ORDER,
            OutboxTypeEnum::NEW_CUSTOMER
            => MailroomCodeEnum::USER_NOTIFICATION,
            OutboxTypeEnum::MARKETING,
            OutboxTypeEnum::NEWSLETTER,
            => MailroomCodeEnum::MARKETING,
            OutboxTypeEnum::SHOP_PROSPECT,
            => MailroomCodeEnum::LEADS,
            OutboxTypeEnum::CUSTOMER_PROSPECT,
            => MailroomCodeEnum::CUSTOMER_LEADS,
            OutboxTypeEnum::TEST,
            => MailroomCodeEnum::TESTS,
        };
    }
}
