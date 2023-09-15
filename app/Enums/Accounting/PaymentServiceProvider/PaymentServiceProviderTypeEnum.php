<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Thu, 27 Apr 2023 11:53:10 Central European Summer Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Enums\Accounting\PaymentServiceProvider;

use App\Enums\EnumHelperTrait;

enum PaymentServiceProviderTypeEnum: string
{
    use EnumHelperTrait;

    case ACCOUNT          = 'account';
    case CASH             = 'cash';
    case BANK             = 'bank';
    case GATEWAY          = 'gateway';
    case CASH_ON_DELIVERY = 'cod';
    case BNPL             = 'bnpl';

    public static function labels(): array
    {
        return [
            'account' => __('Account'),
            'cash'    => __('Cash'),
            'bank'    => __('Bank'),
            'gateway' => __('Payment Gateway'),
            'cod'     => __('Cash On Delivery'),
            'bnpl'    => __('Buy Now Pay Later'),
        ];
    }

}
