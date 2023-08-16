<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:29:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToTenant;
use App\Models\Tenancy\Tenant;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\PortfolioWebsite
 *
 * @property int $id
 * @property int $tenant_id
 * @property string $slug
 * @property string $code
 * @property string $domain
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Banner> $banners
 * @property-read int|null $banners_count
 * @property-read \App\Models\Portfolio\PortfolioWebsiteStats|null $stats
 * @property-read Tenant $tenant
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static \Database\Factories\Portfolio\PortfolioWebsiteFactory factory($count = null, $state = [])
 * @method static Builder|PortfolioWebsite newModelQuery()
 * @method static Builder|PortfolioWebsite newQuery()
 * @method static Builder|PortfolioWebsite onlyTrashed()
 * @method static Builder|PortfolioWebsite query()
 * @method static Builder|PortfolioWebsite whereCode($value)
 * @method static Builder|PortfolioWebsite whereCreatedAt($value)
 * @method static Builder|PortfolioWebsite whereData($value)
 * @method static Builder|PortfolioWebsite whereDeletedAt($value)
 * @method static Builder|PortfolioWebsite whereDomain($value)
 * @method static Builder|PortfolioWebsite whereId($value)
 * @method static Builder|PortfolioWebsite whereName($value)
 * @method static Builder|PortfolioWebsite whereSlug($value)
 * @method static Builder|PortfolioWebsite whereTenantId($value)
 * @method static Builder|PortfolioWebsite whereUpdatedAt($value)
 * @method static Builder|PortfolioWebsite withTrashed()
 * @method static Builder|PortfolioWebsite withoutTrashed()
 * @mixin \Eloquent
 */
class PortfolioWebsite extends Model
{
    use HasSlug;
    use SoftDeletes;
    use HasUniversalSearch;
    use HasFactory;
    use BelongsToTenant;

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
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
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(PortfolioWebsiteStats::class);
    }

    public function banners(): BelongsToMany
    {
        return $this->belongsToMany(Banner::class)->using(BannerPortfolioWebsite::class)
            ->withTimestamps();
    }

}
