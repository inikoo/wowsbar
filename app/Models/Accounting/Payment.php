<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:09:49 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use App\Actions\Accounting\PaymentAccount\Hydrators\PaymentAccountHydratePayments;
use App\Actions\Accounting\PaymentServiceProvider\Hydrators\PaymentServiceProviderHydratePayments;
use App\Enums\Accounting\Payment\PaymentStateEnum;
use App\Enums\Accounting\Payment\PaymentStatusEnum;
use App\Enums\Accounting\Payment\PaymentSubsequentStatusEnum;
use App\Enums\Accounting\Payment\PaymentTypeEnum;
use App\Models\Assets\Currency;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Payments\Payment
 *
 * @property int $id
 * @property int $shop_id
 * @property int $payment_account_id
 * @property int $customer_id
 * @property PaymentTypeEnum $type
 * @property string $reference
 * @property string|null $webhook_id
 * @property string $slug
 * @property PaymentStatusEnum $status
 * @property PaymentStateEnum $state
 * @property PaymentSubsequentStatusEnum|null $subsequent_status
 * @property string $amount
 * @property string $org_amount
 * @property array $data
 * @property string $date Most relevant date at current state
 * @property string|null $completed_at
 * @property string|null $cancelled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property bool $with_refund
 * @property-read Currency|null $currency
 * @property-read Customer $customer
 * @property-read \App\Models\Accounting\PaymentAccount $paymentAccount
 * @property-read Shop $shop
 * @property-read UniversalSearch|null $universalSearch
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment onlyTrashed()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereCancelledAt($value)
 * @method static Builder|Payment whereCompletedAt($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereCustomerId($value)
 * @method static Builder|Payment whereData($value)
 * @method static Builder|Payment whereDate($value)
 * @method static Builder|Payment whereDeleteComment($value)
 * @method static Builder|Payment whereDeletedAt($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereOrgAmount($value)
 * @method static Builder|Payment wherePaymentAccountId($value)
 * @method static Builder|Payment whereReference($value)
 * @method static Builder|Payment whereShopId($value)
 * @method static Builder|Payment whereSlug($value)
 * @method static Builder|Payment whereState($value)
 * @method static Builder|Payment whereStatus($value)
 * @method static Builder|Payment whereSubsequentStatus($value)
 * @method static Builder|Payment whereType($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @method static Builder|Payment whereWebhookId($value)
 * @method static Builder|Payment whereWithRefund($value)
 * @method static Builder|Payment withTrashed()
 * @method static Builder|Payment withoutTrashed()
 * @mixin Eloquent
 */
class Payment extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasFactory;

    protected $casts = [
        'data'              => 'array',
        'state'             => PaymentStateEnum::class,
        'status'            => PaymentStatusEnum::class,
        'subsequent_status' => PaymentSubsequentStatusEnum::class,
        'type'              => PaymentTypeEnum::class
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(
            function (Payment $payment) {
                $payment->type = $payment->amount >= 0 ? PaymentTypeEnum::PAYMENT : PaymentTypeEnum::REFUND;
            }
        );

        static::created(
            function (Payment $payment) {
                PaymentServiceProviderHydratePayments::dispatch($payment->paymentAccount->paymentServiceProvider);
                PaymentAccountHydratePayments::dispatch($payment->paymentAccount);
                // ShopHydratePayments::dispatch($payment->shop);
            }
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                $slug = $this->reference;

                if ($slug == '') {
                    $slug = 'payment';
                }

                return $slug;
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')->slugsShouldBeNoLongerThan(16);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function paymentAccount(): BelongsTo
    {
        return $this->belongsTo(PaymentAccount::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
