<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:32:21 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tenancy;

use App\Models\Assets\Currency;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\CRM\PublicUser;
use App\Models\Media\Media;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolio\Snapshot;
use App\Models\Portfolio\SnapshotStats;
use App\Models\Traits\HasHistory;
use App\Models\WebsiteUpload;
use App\Models\WebsiteUploadRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * App\Models\Tenancy\Tenant
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string $name
 * @property string $email
 * @property bool $status
 * @property array $data
 * @property array $settings
 * @property int $language_id
 * @property int $timezone_id
 * @property int|null $logo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Banner> $banners
 * @property-read int|null $banners_count
 * @property-read Currency $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Customer> $customers
 * @property-read int|null $customers_count
 * @property-read array $es_audits
 * @property-read Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenancy\TenantPortfolioStats|null $portfolioStats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WebsiteUpload> $portfolioWebsiteUploads
 * @property-read int|null $portfolio_website_uploads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PortfolioWebsite> $portfolioWebsites
 * @property-read int|null $portfolio_websites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PublicUser> $publicUsers
 * @property-read int|null $public_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SnapshotStats> $snapshotStats
 * @property-read int|null $snapshot_stats_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read \App\Models\Tenancy\TenantStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WebsiteUploadRecord> $websiteUploadRecords
 * @property-read int|null $website_upload_records_count
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\Tenancy\TenantFactory factory($count = null, $state = [])
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereLogoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tenant extends SpatieTenant implements HasMedia, Auditable
{
    use HasFactory;
    use HasSlug;
    use HasHistory;
    use InteractsWithMedia;


    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function stats(): HasOne
    {
        return $this->hasOne(TenantStats::class);
    }

    public function portfolioStats(): HasOne
    {
        return $this->hasOne(TenantPortfolioStats::class);
    }


    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function portfolioWebsites(): HasMany
    {
        return $this->hasMany(PortfolioWebsite::class);
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

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }
}
