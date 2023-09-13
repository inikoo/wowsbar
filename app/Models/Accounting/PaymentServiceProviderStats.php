<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 10:07:25 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Payments\PaymentServiceProviderStats
 *
 * @property int $id
 * @property int $payment_service_provider_id
 * @property int $number_accounts
 * @property int $number_payment_records
 * @property int $number_payments
 * @property int $number_refunds
 * @property string $tc_amount tenant currency, amount_successfully_paid-amount_returned
 * @property string $tc_amount_successfully_paid
 * @property string $tc_amount_refunded
 * @property string $gc_amount Group currency, amount_successfully_paid-amount_returned
 * @property string $gc_amount_successfully_paid
 * @property string $gc_amount_refunded
 * @property int $number_payment_records_state_in_process
 * @property int $number_payments_state_in_process
 * @property int $number_refunds_state_in_process
 * @property int $number_payment_records_state_approving
 * @property int $number_payments_state_approving
 * @property int $number_refunds_state_approving
 * @property int $number_payment_records_state_completed
 * @property int $number_payments_state_completed
 * @property int $number_refunds_state_completed
 * @property int $number_payment_records_state_cancelled
 * @property int $number_payments_state_cancelled
 * @property int $number_refunds_state_cancelled
 * @property int $number_payment_records_state_error
 * @property int $number_payments_state_error
 * @property int $number_refunds_state_error
 * @property int $number_payment_records_state_declined
 * @property int $number_payments_state_declined
 * @property int $number_refunds_state_declined
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Accounting\PaymentServiceProvider $paymentServiceProvider
 * @method static Builder|PaymentServiceProviderStats newModelQuery()
 * @method static Builder|PaymentServiceProviderStats newQuery()
 * @method static Builder|PaymentServiceProviderStats query()
 * @method static Builder|PaymentServiceProviderStats whereCreatedAt($value)
 * @method static Builder|PaymentServiceProviderStats whereGcAmount($value)
 * @method static Builder|PaymentServiceProviderStats whereGcAmountRefunded($value)
 * @method static Builder|PaymentServiceProviderStats whereGcAmountSuccessfullyPaid($value)
 * @method static Builder|PaymentServiceProviderStats whereId($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberAccounts($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecords($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecordsStateApproving($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecordsStateCancelled($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecordsStateCompleted($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecordsStateDeclined($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecordsStateError($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentRecordsStateInProcess($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPayments($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentsStateApproving($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentsStateCancelled($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentsStateCompleted($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentsStateDeclined($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentsStateError($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberPaymentsStateInProcess($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefunds($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefundsStateApproving($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefundsStateCancelled($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefundsStateCompleted($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefundsStateDeclined($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefundsStateError($value)
 * @method static Builder|PaymentServiceProviderStats whereNumberRefundsStateInProcess($value)
 * @method static Builder|PaymentServiceProviderStats wherePaymentServiceProviderId($value)
 * @method static Builder|PaymentServiceProviderStats whereTcAmount($value)
 * @method static Builder|PaymentServiceProviderStats whereTcAmountRefunded($value)
 * @method static Builder|PaymentServiceProviderStats whereTcAmountSuccessfullyPaid($value)
 * @method static Builder|PaymentServiceProviderStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentServiceProviderStats extends Model
{
    protected $table = 'payment_service_provider_stats';

    protected $guarded = [];

    public function paymentServiceProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentServiceProvider::class);
    }
}
