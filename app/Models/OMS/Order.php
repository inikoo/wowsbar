<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\OMS;

use App\Enums\OMS\Order\OrderStateEnum;
use App\Enums\OMS\Order\OrderStatusEnum;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\Payment;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\OMS\Order
 *
 * @property int $id
 * @property string $slug
 * @property int $shop_id
 * @property int $customer_id
 * @property string|null $number
 * @property string|null $customer_number Customers own order number
 * @property OrderStateEnum $state
 * @property OrderStatusEnum $status
 * @property string $date
 * @property string|null $submitted_at
 * @property string|null $in_warehouse_at
 * @property string|null $handling_at
 * @property string|null $packed_at
 * @property string|null $finalised_at
 * @property string|null $dispatched_at
 * @property string|null $settled_at
 * @property string|null $cancelled_at
 * @property bool $is_invoiced
 * @property bool|null $is_picking_on_hold
 * @property bool|null $can_dispatch
 * @property string $items_discounts
 * @property string $items_net
 * @property int $currency_id
 * @property string $exchange
 * @property string $charges
 * @property string|null $shipping
 * @property string $net
 * @property string $tax
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read Collection<int, Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read Collection<int, \App\Models\Accounting\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Market\Shop $shop
 * @property-read \App\Models\OMS\OrderStats|null $stats
 * @property-read Collection<int, \App\Models\OMS\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order onlyTrashed()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCanDispatch($value)
 * @method static Builder|Order whereCancelledAt($value)
 * @method static Builder|Order whereCharges($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCurrencyId($value)
 * @method static Builder|Order whereCustomerId($value)
 * @method static Builder|Order whereCustomerNumber($value)
 * @method static Builder|Order whereData($value)
 * @method static Builder|Order whereDate($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereDispatchedAt($value)
 * @method static Builder|Order whereExchange($value)
 * @method static Builder|Order whereFinalisedAt($value)
 * @method static Builder|Order whereHandlingAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereInWarehouseAt($value)
 * @method static Builder|Order whereIsInvoiced($value)
 * @method static Builder|Order whereIsPickingOnHold($value)
 * @method static Builder|Order whereItemsDiscounts($value)
 * @method static Builder|Order whereItemsNet($value)
 * @method static Builder|Order whereNet($value)
 * @method static Builder|Order whereNumber($value)
 * @method static Builder|Order wherePackedAt($value)
 * @method static Builder|Order whereSettledAt($value)
 * @method static Builder|Order whereShipping($value)
 * @method static Builder|Order whereShopId($value)
 * @method static Builder|Order whereSlug($value)
 * @method static Builder|Order whereState($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereSubmittedAt($value)
 * @method static Builder|Order whereTax($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @mixin Eloquent
 */
class Order extends Model
{
    use SoftDeletes;
    use HasUniversalSearch;
    use HasFactory;

    protected $casts = [
        'data'   => 'array',
        'state'  => OrderStateEnum::class,
        'status' => OrderStatusEnum::class
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('number')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }


    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function payments(): MorphToMany
    {
        return $this->morphToMany(Payment::class, 'paymentable')->withTimestamps()->withPivot(['amount','share']);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(OrderStats::class);
    }


}
