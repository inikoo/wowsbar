<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 11:19:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentGateway\Xendit\Channels\Invoice;

use App\Models\Accounting\Payment;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Xendit\Invoice;
use Xendit\Xendit;

class MakePaymentUsingInvoice
{
    use AsAction;
    use WithAttributes;

    private bool $asAction=false;

    public function handle(Payment $payment): array
    {
        Xendit::setApiKey(Arr::get($payment->paymentAccount->paymentServiceProvider->data, 'api_key'));

        $customer = $payment->customer;
        $externalId = $payment->reference;

        $params = [
            'external_id'      => $externalId,
            'amount'           => (int) $payment->amount,
            'description'      => 'Invoice for ' . $customer->name,
            'invoice_duration' => 3600,
            'customer'         => [
                'surname'       => $customer->name,
                'email'         => $customer->email
            ],
            'success_redirect_url' => url('/org'),
            'failure_redirect_url' => url('/org')
        ];

        $response = Invoice::create($params);
        $payment->update(['data' => $response]);

        return $response;
    }
}
