<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Oct 2022 19:00:13 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Market;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Organisation\Market\ProductCategoryStats
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
    use UsesTenantConnection;

    protected $table = 'product_category_stats';

    protected $guarded = [];


}
