<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 11:19:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentGateway\Xendit\Cards;

use App\Models\Accounting\PaymentAccount;
use App\Models\Accounting\PaymentServiceProvider;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class MakePaymentUsingCard
{
    use AsAction;
    use WithAttributes;

    private bool $asAction=false;

    public function handle(PaymentServiceProvider $paymentServiceProvider, array $modelData): PaymentAccount
    {
        //
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:tenant.payment_accounts', 'between:2,9', 'alpha_dash'],
            'name' => ['required', 'max:250', 'string'],
        ];
    }
}
