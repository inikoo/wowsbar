<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Catalogue;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Catalogue\ProductCategoryStats
 *
 * @property int $id
 * @property int $product_category_id
 * @property int $number_sub_product_categories
 * @property int $number_families
 * @property int $number_products
 * @property int $number_products_state_in_process
 * @property int $number_products_state_active
 * @property int $number_products_state_discontinuing
 * @property int $number_products_state_discontinued
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Catalogue\ProductCategory $productCategory
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberFamilies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberProductsStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberProductsStateDiscontinued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberProductsStateDiscontinuing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberProductsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereNumberSubProductCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductCategoryStats extends Model
{
    protected $table = 'product_category_stats';

    protected $guarded = [];

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
