<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use App\Enums\Organisation\Market\Shop\ShopStateEnum;
use App\Enums\Organisation\Market\Shop\ShopTypeEnum;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\Payment;
use App\Models\Accounting\PaymentAccount;
use App\Models\Accounting\PaymentAccountShop;
use App\Models\Accounting\PaymentServiceProvider;
use App\Models\Accounting\PaymentServiceProviderShop;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Timezone;
use App\Models\CRM\Customer;
use App\Models\CRM\Prospect;
use App\Models\Helpers\SerialReference;
use App\Models\OMS\Order;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Market\Shop
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string $name
 * @property string|null $company_name
 * @property string|null $contact_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property int|null $address_id
 * @property array $location
 * @property ShopStateEnum $state
 * @property ShopTypeEnum $type
 * @property string|null $open_at
 * @property string|null $closed_at
 * @property int $country_id
 * @property int $language_id
 * @property int $currency_id
 * @property int $timezone_id
 * @property array $data
 * @property array $settings
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Market\ShopAccountingStats|null $accountingStats
 * @property-read Country $country
 * @property-read \App\Models\Market\ShopCRMStats|null $crmStats
 * @property-read Currency $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PaymentAccount> $paymentAccounts
 * @property-read int|null $payment_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PaymentServiceProvider> $paymentServiceProviders
 * @property-read int|null $payment_service_providers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Market\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Prospect> $prospects
 * @property-read int|null $prospects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SerialReference> $serialReferences
 * @property-read int|null $serial_references_count
 * @property-read \App\Models\Market\ShopStats|null $stats
 * @property-read Timezone $timezone
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read Website|null $website
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereIdentityDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereIdentityDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereOpenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop withoutTrashed()
 * @mixin \Eloquent
 */
class Shop extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasFactory;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'location' => 'array',
        'type'     => ShopTypeEnum::class,
        'state'    => ShopStateEnum::class
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
        'location' => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(16);
    }

    public function crmStats(): HasOne
    {
        return $this->hasOne(ShopCRMStats::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(ShopStats::class);
    }

    public function accountingStats(): HasOne
    {
        return $this->hasOne(ShopAccountingStats::class);
    }


    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function prospects(): HasMany
    {
        return $this->hasMany(Prospect::class);
    }

    public function scopedProspects(): MorphMany
    {
        return $this->morphMany(Prospect::class,'scope');
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function website(): HasOne
    {
        return $this->hasOne(Website::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    public function paymentServiceProviders(): BelongsToMany
    {
        return $this->belongsToMany(PaymentServiceProvider::class)->using(PaymentServiceProviderShop::class)
            ->withTimestamps();
    }
    public function paymentAccounts(): BelongsToMany
    {
        return $this->belongsToMany(PaymentAccount::class)->using(PaymentAccountShop::class)
            ->withTimestamps();
    }

    public function accounts(): PaymentAccount
    {
        return $this->paymentAccounts()->where('payment_accounts.data->service-code', 'accounts')->first();
    }

    public function serialReferences(): MorphMany
    {
        return $this->morphMany(SerialReference::class, 'container');
    }



}
