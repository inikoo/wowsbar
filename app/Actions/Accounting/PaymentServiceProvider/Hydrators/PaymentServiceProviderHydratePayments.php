<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider\Hydrators;

use App\Enums\Accounting\Payment\PaymentStateEnum;
use App\Models\Accounting\PaymentServiceProvider;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class PaymentServiceProviderHydratePayments
{
    use AsAction;


    public function handle(PaymentServiceProvider $paymentServiceProvider): void
    {
        $paymentRecords = $paymentServiceProvider->payments()->count();
        $refunds        = $paymentServiceProvider->payments()->where('type', 'refund')->count();

        $amountTenantCurrencySuccessfullyPaid = $paymentServiceProvider->payments()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->sum('org_amount');
        $amountTenantCurrencyRefunded         = $paymentServiceProvider->payments()
            ->where('type', 'refund')
            ->where('status', 'success')
            ->sum('org_amount');

        $stats = [
            'number_payment_records'       => $paymentRecords,
            'number_payments'              => $paymentRecords - $refunds,
            'number_refunds'               => $refunds,
            'org_amount'                   => $amountTenantCurrencySuccessfullyPaid + $amountTenantCurrencyRefunded,
            'org_amount_successfully_paid' => $amountTenantCurrencySuccessfullyPaid,
            'org_amount_refunded'          => $amountTenantCurrencyRefunded
        ];


        $stateCounts = $paymentServiceProvider->payments()
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_payment_records_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $stateCounts =$paymentServiceProvider->payments()->where('type', 'payment')
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_payments_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $stateCounts = $paymentServiceProvider->payments()->where('type', 'refund')
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_refunds_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $paymentServiceProvider->stats->update($stats);
    }

    public function getJobUniqueId(PaymentServiceProvider $paymentServiceProvider): int
    {
        return $paymentServiceProvider->id;
    }
}
