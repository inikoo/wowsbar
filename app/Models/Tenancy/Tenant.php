<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:32:21 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tenancy;

use App\Models\Assets\Currency;
use App\Models\Auth\PublicUser;
use App\Models\Auth\User;
use App\Models\Media\Media;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolio\Snapshot;
use App\Models\Portfolio\SnapshotStats;
use App\Models\Traits\HasHistory;
use App\Models\WebsiteUploadRecord;
use Illuminate\Database\Eloquent\Builder;
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
 * @property int $country_id
 * @property int $language_id
 * @property int $timezone_id
 * @property int $currency_id tenant accounting currency
 * @property int|null $logo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Banner> $banners
 * @property-read int|null $banners_count
 * @property-read Currency $currency
 * @property-read array $es_audits
 * @property-read Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenancy\TenantPortfolioStats|null $portfolioStats
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
 * @method static Builder|Tenant newModelQuery()
 * @method static Builder|Tenant newQuery()
 * @method static Builder|Tenant query()
 * @method static Builder|Tenant whereCode($value)
 * @method static Builder|Tenant whereCountryId($value)
 * @method static Builder|Tenant whereCreatedAt($value)
 * @method static Builder|Tenant whereCurrencyId($value)
 * @method static Builder|Tenant whereData($value)
 * @method static Builder|Tenant whereDeletedAt($value)
 * @method static Builder|Tenant whereEmail($value)
 * @method static Builder|Tenant whereId($value)
 * @method static Builder|Tenant whereLanguageId($value)
 * @method static Builder|Tenant whereLogoId($value)
 * @method static Builder|Tenant whereName($value)
 * @method static Builder|Tenant whereSettings($value)
 * @method static Builder|Tenant whereSlug($value)
 * @method static Builder|Tenant whereStatus($value)
 * @method static Builder|Tenant whereTimezoneId($value)
 * @method static Builder|Tenant whereUpdatedAt($value)
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

    public function publicUsers(): HasMany
    {
        return $this->hasMany(PublicUser::class);
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }
}
