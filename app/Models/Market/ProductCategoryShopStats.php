<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Market\ProductCategoryStats
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryShopStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryShopStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategoryShopStats query()
 * @mixin \Eloquent
 */
class ProductCategoryShopStats extends Model
{
    protected $table = 'product_category_shop_stats';

    protected $guarded = [];


}
