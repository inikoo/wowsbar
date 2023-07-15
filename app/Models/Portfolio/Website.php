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
use Database\Factories\Portfolio\WebsiteFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\Website
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\ContentBlock> $contentBlocks
 * @property-read int|null $content_blocks_count
 * @property-read \App\Models\Portfolio\WebsiteStats|null $stats
 * @property-read Tenant $tenant
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static WebsiteFactory factory($count = null, $state = [])
 * @method static Builder|Website newModelQuery()
 * @method static Builder|Website newQuery()
 * @method static Builder|Website onlyTrashed()
 * @method static Builder|Website query()
 * @method static Builder|Website whereCode($value)
 * @method static Builder|Website whereCreatedAt($value)
 * @method static Builder|Website whereData($value)
 * @method static Builder|Website whereDeletedAt($value)
 * @method static Builder|Website whereDomain($value)
 * @method static Builder|Website whereId($value)
 * @method static Builder|Website whereName($value)
 * @method static Builder|Website whereSlug($value)
 * @method static Builder|Website whereTenantId($value)
 * @method static Builder|Website whereUpdatedAt($value)
 * @method static Builder|Website withTrashed()
 * @method static Builder|Website withoutTrashed()
 * @mixin \Eloquent
 */
class Website extends Model
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
        return $this->hasOne(WebsiteStats::class);
    }

    public function contentBlocks(): BelongsToMany
    {
        return $this->belongsToMany(ContentBlock::class)->using(ContentBlockWebsite::class)
            ->withTimestamps();
    }

}
