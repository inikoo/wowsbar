<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:32:21 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tenancy;

use App\Models\Assets\Currency;
use App\Models\Auth\User;
use App\Models\Media\Media;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Spatie\Multitenancy\TenantCollection;
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ContentBlock> $contentBlocks
 * @property-read int|null $content_blocks_count
 * @property-read Currency $currency
 * @property-read Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenancy\TenantStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Website> $websites
 * @property-read int|null $websites_count
 * @method static TenantCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\Tenancy\TenantFactory factory($count = null, $state = [])
 * @method static TenantCollection<int, static> get($columns = ['*'])
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
class Tenant extends SpatieTenant implements HasMedia
{
    use HasFactory;
    use HasSlug;
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

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ContentBlock::class);
    }

    public function logo(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'logo_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }
}
