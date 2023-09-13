<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 11:19:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentGateway\Xendit\Cards;

use App\Models\Accounting\PaymentAccount;
use App\Models\Accounting\PaymentServiceProvider;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Xendit\PaymentChannels;
use Xendit\Xendit;

class CreateToken
{
    use AsAction;
    use WithAttributes;

    private bool $asAction=false;

    public function handle(float $amount, array $cardData, array $billingDetail): PaymentAccount
    {
        $paymentData = [
            'amount' => $amount,
            'card_data' => Arr::except($cardData, 'cvn'),
            'is_multiple_use' => false,
            'should_authenticate' => true,
            'billing_details' => $billingDetail
        ];

//        \Xendit\Cards::create()
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:tenant.payment_accounts', 'between:2,9', 'alpha_dash'],
            'name' => ['required', 'max:250', 'string'],
        ];
    }
}
