<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateDepartments;
use App\Enums\Market\ProductCategory\ProductCategoryStateEnum;
use App\Models\BI\SalesStats;
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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Market\Department
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string|null $name
 * @property string|null $description
 * @property int|null $image_id
 * @property int|null $shop_id
 * @property int $parent_id
 * @property string $parent_type
 * @property string $type
 * @property bool $is_family
 * @property mixed|null $state
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $source_department_id
 * @property int|null $source_family_id
 * @property-read Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, ProductCategory> $departments
 * @property-read int|null $departments_count
 * @property-read array $es_audits
 * @property-read Model|\Eloquent $parent
 * @property-read UniversalSearch|null $universalSearch
 * @method static Builder|ProductCategory newModelQuery()
 * @method static Builder|ProductCategory newQuery()
 * @method static Builder|ProductCategory onlyTrashed()
 * @method static Builder|ProductCategory query()
 * @method static Builder|ProductCategory whereCode($value)
 * @method static Builder|ProductCategory whereCreatedAt($value)
 * @method static Builder|ProductCategory whereData($value)
 * @method static Builder|ProductCategory whereDeletedAt($value)
 * @method static Builder|ProductCategory whereDescription($value)
 * @method static Builder|ProductCategory whereId($value)
 * @method static Builder|ProductCategory whereImageId($value)
 * @method static Builder|ProductCategory whereIsFamily($value)
 * @method static Builder|ProductCategory whereName($value)
 * @method static Builder|ProductCategory whereParentId($value)
 * @method static Builder|ProductCategory whereParentType($value)
 * @method static Builder|ProductCategory whereShopId($value)
 * @method static Builder|ProductCategory whereSlug($value)
 * @method static Builder|ProductCategory whereSourceDepartmentId($value)
 * @method static Builder|ProductCategory whereSourceFamilyId($value)
 * @method static Builder|ProductCategory whereState($value)
 * @method static Builder|ProductCategory whereType($value)
 * @method static Builder|ProductCategory whereUpdatedAt($value)
 * @method static Builder|ProductCategory withTrashed()
 * @method static Builder|ProductCategory withoutTrashed()
 * @mixin Eloquent
 */
class ProductCategory extends Model implements Auditable
{
    use HasSlug;
    use SoftDeletes;
    use UsesTenantConnection;
    use HasUniversalSearch;
    use HasFactory;
    use HasHistory;

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

    protected static function booted(): void
    {
        static::updated(function (ProductCategory $department) {
            if ($department->wasChanged('state')) {
                ShopHydrateDepartments::dispatch($department->shop);
            }
        });
    }

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


    public function stats(): HasOne
    {
        return $this->hasOne(ProductCategoryStats::class);
    }

    public function salesStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope', 'sales');
    }

    public function salesTenantCurrencyStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope', 'sales-tenant-currency');
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function departments(): MorphMany
    {
        return $this->morphMany(ProductCategory::class, 'parent');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'parent');
    }
}
