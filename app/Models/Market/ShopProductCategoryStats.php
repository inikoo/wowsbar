<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Market\ShopProductCategoryStats
 *
 * @property int $id
 * @property int $shop_product_category_id
 * @property int $number_sub_product_categories
 * @property int $number_families
 * @property int $number_products
 * @property int $number_products_state_in_process
 * @property int $number_products_state_active
 * @property int $number_products_state_discontinuing
 * @property int $number_products_state_discontinued
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberFamilies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberProductsStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberProductsStateDiscontinued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberProductsStateDiscontinuing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberProductsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereNumberSubProductCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereShopProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductCategoryStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopProductCategoryStats extends Model
{
    protected $table = 'shop_product_category_stats';

    protected $guarded = [];


}
