<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 11:19:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentGateway\Xendit\Channels\Cards;

use App\Actions\Accounting\PaymentGateway\Xendit\Traits\HasCredentials;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Xendit\Cards;

class MakePaymentUsingCard
{
    use AsAction;
    use WithAttributes;
    use HasCredentials;

    private bool $asAction=false;

    public function handle(float $amount, $paymentDetail): array
    {
        $params = [
            'token_id' => $paymentDetail['token_id'],
            'external_id' => 'card_' . time(),
            'authentication_id' => $paymentDetail['authentication_id'],
            'amount' => $amount,
            'capture' => false
        ];

        return Cards::create($params);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:tenant.payment_accounts', 'between:2,9', 'alpha_dash'],
            'name' => ['required', 'max:250', 'string'],
        ];
    }
}
