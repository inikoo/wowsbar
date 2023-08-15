<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Website;

use App\Enums\Organisation\Website\Website\WebsiteEngineEnum;
use App\Enums\Web\Website\WebsiteStateEnum;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Organisation\Website\Website
 *
 * @property mixed $state
 * @property WebsiteEngineEnum $engine
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read array $es_audits
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read \App\Models\Organisation\Website\WebsiteStats|null $webStats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation\Website\Webpage> $webpages
 * @property-read int|null $webpages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Website newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Website query()
 * @method static \Illuminate\Database\Eloquent\Builder|Website withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Website withoutTrashed()
 * @mixin \Eloquent
 */
class Website extends Model implements Auditable
{
    use UsesTenantConnection;
    use HasSlug;
    use SoftDeletes;
    use HasHistory;
    use HasUniversalSearch;

    protected $casts = [
        'data'      => 'array',
        'settings'  => 'array',
        'structure' => 'array',
        'state'     => WebsiteStateEnum::class,
        'engine'    => WebsiteEngineEnum::class
    ];

    protected $attributes = [
        'data'      => '{}',
        'settings'  => '{}',
        'structure' => '{}',
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


    public function webpages(): HasMany
    {
        return $this->hasMany(Webpage::class);
    }

    public function webStats(): HasOne
    {
        return $this->hasOne(WebsiteStats::class);
    }
}
