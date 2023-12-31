<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentAccount\Hydrators;

use App\Enums\Accounting\Payment\PaymentStateEnum;
use App\Models\Accounting\PaymentAccount;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class PaymentAccountHydratePayments
{
    use AsAction;


    public function handle(PaymentAccount $paymentAccount): void
    {
        $paymentRecords = $paymentAccount->payments()->count();
        $refunds        = $paymentAccount->payments()->where('type', 'refund')->count();

        $amountTenantCurrencySuccessfullyPaid = $paymentAccount->payments()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->sum('org_amount');
        $amountTenantCurrencyRefunded         = $paymentAccount->payments()
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

        $stateCounts =$paymentAccount->payments()
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_payment_records_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $stateCounts =$paymentAccount->payments()->where('type', 'payment')
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_payments_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $stateCounts =$paymentAccount->payments()->where('type', 'refund')
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_refunds_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $paymentAccount->stats->update($stats);
    }

    public function getJobUniqueId(PaymentAccount $paymentAccount): int
    {
        return $paymentAccount->id;
    }
}
