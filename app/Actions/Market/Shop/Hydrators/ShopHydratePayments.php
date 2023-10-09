<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Enums\Accounting\Payment\PaymentStateEnum;
use App\Models\Accounting\Payment;
use App\Models\Market\Shop;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydratePayments implements ShouldBeUnique
{
    use AsAction;


    public function handle(Shop $shop): void
    {
        $paymentRecords = $shop->payments()->count();
        $refunds        = $shop->payments()->where('type', 'refund')->count();

        $amountTenantCurrencySuccessfullyPaid = $shop->payments()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->sum('org_amount');
        $amountTenantCurrencyRefunded         = $shop->payments()
            ->where('type', 'refund')
            ->where('status', 'success')
            ->sum('org_amount');

        $amountSuccessfullyPaid = $shop->payments()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->sum('amount');
        $amountRefunded         = $shop->payments()
            ->where('type', 'refund')
            ->where('status', 'success')
            ->sum('amount');


        $stats = [
            'number_payment_records'       => $paymentRecords,
            'number_payments'              => $paymentRecords - $refunds,
            'number_refunds'               => $refunds,
            'amount'                       => $amountSuccessfullyPaid + $amountTenantCurrencyRefunded,
            'amount_successfully_paid'     => $amountSuccessfullyPaid,
            'amount_refunded'              => $amountRefunded,
            'org_amount'                   => $amountTenantCurrencySuccessfullyPaid + $amountTenantCurrencyRefunded,
            'org_amount_successfully_paid' => $amountTenantCurrencySuccessfullyPaid,
            'org_amount_refunded'          => $amountTenantCurrencyRefunded


        ];

        $stateCounts = Payment::where('shop_id', $shop->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_payment_records_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $stateCounts = Payment::where('shop_id', $shop->id)->where('type', 'payment')
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_payments_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }

        $stateCounts = Payment::where('shop_id', $shop->id)->where('type', 'refund')
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach (PaymentStateEnum::cases() as $state) {
            $stats["number_refunds_state_{$state->snake()}"] = Arr::get($stateCounts, $state->value, 0);
        }


        $shop->accountingStats()->update($stats);
    }

    public function getJobUniqueId(Shop $shop): string
    {
        return $shop->id;
    }
}
