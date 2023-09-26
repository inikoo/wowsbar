<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use App\Enums\Catalogue\ProductCategory\ProductCategoryStateEnum;
use App\Models\BI\SalesStats;
use App\Models\Catalogue\ProductCategory;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Market\ShopProductCategory
 *
 * @property int $id
 * @property string $slug
 * @property int|null $product_category_id
 * @property int|null $shop_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property ProductCategoryStateEnum $state
 * @property-read Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, ShopProductCategory> $departments
 * @property-read int|null $departments_count
 * @property-read array $es_audits
 * @property-read Model|\Eloquent $parent
 * @property-read ProductCategory|null $productCategory
 * @property-read Collection<int, \App\Models\Market\ShopProduct> $products
 * @property-read int|null $products_count
 * @property-read SalesStats|null $salesStats
 * @property-read \App\Models\Market\Shop|null $shop
 * @property-read \App\Models\Market\ShopProductCategoryStats|null $stats
 * @property-read UniversalSearch|null $universalSearch
 * @method static Builder|ShopProductCategory newModelQuery()
 * @method static Builder|ShopProductCategory newQuery()
 * @method static Builder|ShopProductCategory query()
 * @method static Builder|ShopProductCategory whereCreatedAt($value)
 * @method static Builder|ShopProductCategory whereDeletedAt($value)
 * @method static Builder|ShopProductCategory whereId($value)
 * @method static Builder|ShopProductCategory whereProductCategoryId($value)
 * @method static Builder|ShopProductCategory whereShopId($value)
 * @method static Builder|ShopProductCategory whereSlug($value)
 * @method static Builder|ShopProductCategory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ShopProductCategory extends Pivot implements Auditable
{
    use HasSlug;
    use HasUniversalSearch;
    use HasFactory;
    use HasHistory;

    protected $table = 'shop_product_category';

    protected $guarded = [];

    protected $casts = [
        'data'  => 'array',
        'state' => ProductCategoryStateEnum::class
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(64);
    }


    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }


    public function stats(): HasOne
    {
        return $this->hasOne(ShopProductCategoryStats::class);
    }

    public function salesStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope', 'sales');
    }


    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function departments(): MorphMany
    {
        return $this->morphMany(ShopProductCategory::class, 'parent');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(ShopProduct::class, 'parent');
    }
}
