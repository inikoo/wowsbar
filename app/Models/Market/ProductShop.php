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
use App\Models\Traits\HasImages;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Market\ProductShop
 *
 * @property int $id
 * @property string $slug
 * @property int|null $product_id
 * @property int|null $shop_id
 * @property ProductStateEnum|null $state
 * @property bool|null $status
 * @property string $price unit price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property ProductTypeEnum $type
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read SalesStats|null $salesStats
 * @property-read \App\Models\Market\Shop|null $shop
 * @property-read \App\Models\Market\ProductShopStats|null $stats
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShop whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductShop extends Pivot implements HasMedia
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


    public function stats(): HasOne
    {
        return $this->hasOne(ProductShopStats::class);
    }


}
