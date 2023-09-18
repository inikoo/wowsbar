<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:10:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\CRM\Customer\CustomerStatusEnum;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Models\Assets\Currency;
use App\Models\Auth\User;
use App\Models\Market\Shop;
use App\Models\Media\Media;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolio\Snapshot;
use App\Models\Portfolio\SnapshotStats;
use App\Models\Portfolio\WebsiteUpload;
use App\Models\Portfolio\WebsiteUploadRecord;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Web\Website;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\CRM\Customer
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $reference customer public id
 * @property string|null $name
 * @property string|null $contact_name
 * @property string|null $company_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property string|null $contact_website
 * @property array $location
 * @property CustomerStatusEnum $status
 * @property CustomerStateEnum $state
 * @property CustomerTradeStateEnum $trade_state number of invoices
 * @property array $data
 * @property int $shop_id
 * @property int|null $website_id
 * @property int $language_id
 * @property int $timezone_id
 * @property int|null $image_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Banner> $banners
 * @property-read int|null $banners_count
 * @property-read Currency $currency
 * @property-read Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\CRM\CustomerPortfolioStats|null $portfolioStats
 * @property-read Collection<int, WebsiteUpload> $portfolioWebsiteUploads
 * @property-read int|null $portfolio_website_uploads_count
 * @property-read Collection<int, PortfolioWebsite> $portfolioWebsites
 * @property-read int|null $portfolio_websites_count
 * @property-read Shop $shop
 * @property-read Collection<int, SnapshotStats> $snapshotStats
 * @property-read int|null $snapshot_stats_count
 * @property-read Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read \App\Models\CRM\CustomerStats|null $stats
 * @property-read UniversalSearch|null $universalSearch
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read Website|null $website
 * @property-read Collection<int, WebsiteUploadRecord> $websiteUploadRecords
 * @property-read int|null $website_upload_records_count
 * @method static \Database\Factories\CRM\CustomerFactory factory($count = null, $state = [])
 * @method static Builder|Customer newModelQuery()
 * @method static Builder|Customer newQuery()
 * @method static Builder|Customer onlyTrashed()
 * @method static Builder|Customer query()
 * @method static Builder|Customer whereCompanyName($value)
 * @method static Builder|Customer whereContactName($value)
 * @method static Builder|Customer whereContactWebsite($value)
 * @method static Builder|Customer whereCreatedAt($value)
 * @method static Builder|Customer whereData($value)
 * @method static Builder|Customer whereDeletedAt($value)
 * @method static Builder|Customer whereEmail($value)
 * @method static Builder|Customer whereId($value)
 * @method static Builder|Customer whereIdentityDocumentNumber($value)
 * @method static Builder|Customer whereIdentityDocumentType($value)
 * @method static Builder|Customer whereImageId($value)
 * @method static Builder|Customer whereLanguageId($value)
 * @method static Builder|Customer whereLocation($value)
 * @method static Builder|Customer whereName($value)
 * @method static Builder|Customer wherePhone($value)
 * @method static Builder|Customer whereReference($value)
 * @method static Builder|Customer whereShopId($value)
 * @method static Builder|Customer whereSlug($value)
 * @method static Builder|Customer whereState($value)
 * @method static Builder|Customer whereStatus($value)
 * @method static Builder|Customer whereTimezoneId($value)
 * @method static Builder|Customer whereTradeState($value)
 * @method static Builder|Customer whereUpdatedAt($value)
 * @method static Builder|Customer whereWebsiteId($value)
 * @method static Builder|Customer withTrashed()
 * @method static Builder|Customer withoutTrashed()
 * @mixin Eloquent
 */
class Customer extends Model implements HasMedia
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasPhoto;
    use HasFactory;

    protected $casts = [
        'data'        => 'array',
        'location'    => 'array',
        'state'       => CustomerStateEnum::class,
        'status'      => CustomerStatusEnum::class,
        'trade_state' => CustomerTradeStateEnum::class

    ];

    protected $attributes = [
        'data'     => '{}',
        'location' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('reference')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnCreate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(
            function (Customer $customer) {
                $customer->name = $customer->company_name == '' ? $customer->contact_name : $customer->company_name;
            }
        );

        static::updated(function (Customer $customer) {
            if ($customer->wasChanged(['contact_name', 'company_name'])) {
                $customer->name = $customer->company_name == '' ? $customer->contact_name : $customer->company_name;
            }
        });
    }

    public function stats(): HasOne
    {
        return $this->hasOne(CustomerStats::class);
    }

    public function portfolioStats(): HasOne
    {
        return $this->hasOne(CustomerPortfolioStats::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }


    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }

    public function logo(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'logo_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(Snapshot::class);
    }

    public function snapshotStats(): HasMany
    {
        return $this->hasMany(SnapshotStats::class);
    }

    public function websiteUploadRecords(): HasMany
    {
        return $this->hasMany(WebsiteUploadRecord::class);
    }

    public function portfolioWebsiteUploads(): HasMany
    {
        return $this->hasMany(WebsiteUpload::class);
    }
    public function portfolioWebsites(): HasMany
    {
        return $this->hasMany(PortfolioWebsite::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

}
