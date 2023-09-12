<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateInvoices;
use App\Actions\Market\Shop\Hydrators\ShopHydrateInvoices;
use App\Enums\Accounting\Invoice\InvoiceTypeEnum;
use App\Models\Assets\Currency;
use App\Models\CRM\Customer;
use App\Models\Helpers\Address;
use App\Models\Market\Shop;
use App\Models\OMS\Order;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasTenantAddress;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Accounting\Invoice
 *
 * @property int $id
 * @property string $slug
 * @property string $number
 * @property int $shop_id
 * @property int $customer_id
 * @property int|null $order_id
 * @property InvoiceTypeEnum $type
 * @property int $currency_id
 * @property string $exchange
 * @property string $net
 * @property string $total
 * @property string $payment
 * @property array|null $paid_at
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $source_id
 * @property-read Collection<int, Address> $addresses
 * @property-read Currency $currency
 * @property-read Customer $customer
 * @property-read Collection<int, \App\Models\Accounting\InvoiceTransaction> $invoiceTransactions
 * @property-read Order|null $order
 * @property-read Collection<int, Order> $orders
 * @property-read Shop $shop
 * @property-read \App\Models\Accounting\InvoiceStats|null $stats
 * @property-read UniversalSearch|null $universalSearch
 * @method static \Database\Factories\Accounting\InvoiceFactory factory($count = null, $state = [])
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static Builder|Invoice onlyTrashed()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice withTrashed()
 * @method static Builder|Invoice withoutTrashed()
 * @mixin Eloquent
 */
class Invoice extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    use HasSlug;
    use HasTenantAddress;
    use HasUniversalSearch;
    use HasFactory;

    protected $casts = [
        'type'    => InvoiceTypeEnum::class,
        'data'    => 'array',
        'paid_at' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('number')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    protected static function booted()
    {
        static::deleted(
            function (Invoice $invoice) {
                CustomerHydrateInvoices::dispatch($invoice->customer);
                ShopHydrateInvoices::dispatch($invoice->shop);
            }
        );
    }

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * Relation to main order, usually the only one, used no avoid looping over orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoiceTransactions(): HasMany
    {
        return $this->hasMany(InvoiceTransaction::class);
    }


    /** @noinspection PhpUnused */
    public function setExchangeAttribute($val)
    {
        $this->attributes['exchange'] = sprintf('%.6f', $val);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(InvoiceStats::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
