<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 02 Sept 2022 14:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use App\Enums\Catalogue\Product\ProductStateEnum;
use App\Enums\Catalogue\Product\ProductTypeEnum;
use App\Models\BI\SalesStats;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasImages;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Market\Product
 *
 * @property ProductTypeEnum $type
 * @property ProductStateEnum $state
 * @property-read Collection<int, \App\Models\Market\HistoricProduct> $historicRecords
 * @property-read int|null $historic_records_count
 * @property-read MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read SalesStats|null $salesStats
 * @property-read \App\Models\Market\Shop $shop
 * @property-read \App\Models\Market\ProductStats|null $stats
 * @property-read UniversalSearch|null $universalSearch
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @mixin Eloquent
 */
class Product extends Pivot implements HasMedia
{
    use HasSlug;
    use HasUniversalSearch;
    use HasImages;
    use HasFactory;

    protected $casts = [
        'data'                   => 'array',
        'settings'               => 'array',
        'status'                 => 'boolean',
        'type'                   => ProductTypeEnum::class,
        'state'                  => ProductStateEnum::class,
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(64);
    }


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function salesStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope', 'sales');
    }

    public function historicRecords(): HasMany
    {
        return $this->hasMany(HistoricProduct::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(ProductStats::class);
    }


}
