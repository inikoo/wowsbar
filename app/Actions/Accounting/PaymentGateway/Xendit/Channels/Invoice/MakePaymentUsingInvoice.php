<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 11:19:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentGateway\Xendit\Channels\Invoice;

use App\Actions\Accounting\PaymentGateway\Xendit\Traits\HasCredentials;
use App\Models\Accounting\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Xendit\Invoice;

class MakePaymentUsingInvoice
{
    use AsAction;
    use WithAttributes;
    use HasCredentials;

    private bool $asAction=false;

    public function handle(Payment $payment, array $data = []): array
    {
        $customer = $payment->customer;

        $params = [
            'external_id'      => Str::ulid(),
            'amount'           => $payment->amount,
            'description'      => Arr::get($data, 'description'),
            'invoice_duration' => now()->addDay()->timestamp,
            'customer'         => [
                'given_names'   => $customer->contact_name,
                'surname'       => $customer->name,
                'email'         => $customer->email,
                'mobile_number' => $customer->phone
            ],
            'success_redirect_url' => url('/'),
            'failure_redirect_url' => url('/'),
            'currency'             => 'IDR',
            'payment_methods'      => ['CREDIT_CARD']
        ];

        return Invoice::create($params);
    }
}
