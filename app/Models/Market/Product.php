<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 02 Sept 2022 14:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use App\Enums\Market\Product\ProductTradeUnitCompositionEnum;
use App\Enums\Organisation\Market\Product\ProductStateEnum;
use App\Enums\Organisation\Market\Product\ProductTypeEnum;
use App\Models\BI\SalesStats;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasImages;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Market\Product
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string|null $name
 * @property string|null $description
 * @property ProductTypeEnum $type
 * @property int $owner_id
 * @property string $owner_type
 * @property int $parent_id
 * @property string $parent_type
 * @property int|null $current_historic_product_id
 * @property int|null $shop_id
 * @property ProductStateEnum|null $state
 * @property bool|null $status
 * @property string|null $units units per outer
 * @property string $price unit price
 * @property string|null $rrp RRP per outer
 * @property int|null $available
 * @property int|null $image_id
 * @property array $settings
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $source_id
 * @property ProductTradeUnitCompositionEnum $trade_unit_composition
 * @property-read Collection<int, \App\Models\Market\HistoricProduct> $historicRecords
 * @property-read int|null $historic_records_count
 * @property-read MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read SalesStats|null $salesStats
 * @property-read \App\Models\Market\Shop|null $shop
 * @property-read \App\Models\Market\ProductStats|null $stats
 * @property-read UniversalSearch|null $universalSearch
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product onlyTrashed()
 * @method static Builder|Product query()
 * @method static Builder|Product whereAvailable($value)
 * @method static Builder|Product whereCode($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereCurrentHistoricProductId($value)
 * @method static Builder|Product whereData($value)
 * @method static Builder|Product whereDeletedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImageId($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereOwnerId($value)
 * @method static Builder|Product whereOwnerType($value)
 * @method static Builder|Product whereParentId($value)
 * @method static Builder|Product whereParentType($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereRrp($value)
 * @method static Builder|Product whereSettings($value)
 * @method static Builder|Product whereShopId($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereSourceId($value)
 * @method static Builder|Product whereState($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Product whereType($value)
 * @method static Builder|Product whereUnits($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product withTrashed()
 * @method static Builder|Product withoutTrashed()
 * @mixin Eloquent
 */
class Product extends Model implements HasMedia
{
    use SoftDeletes;
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
        'trade_unit_composition' => ProductTradeUnitCompositionEnum::class
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
    /*
        protected static function booted(): void
        {
            static::updated(function (Product $product) {
                if ($product->wasChanged('state')) {

                    if ($product->family_id) {
                        FamilyHydrateProducts::dispatch($product->family);
                     }
                    ShopHydrateProducts::dispatch($product->shop);
                }
            });
        }
    */

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

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(GroupMedia::class, 'media_product')->withTimestamps()
            ->withPivot(['public', 'owner_type', 'owner_id'])
            ->wherePivot('type', 'image');
    }

    public function barcode(): MorphToMany
    {
        return $this->morphToMany(Barcode::class, 'barcodeable');
    }

}
