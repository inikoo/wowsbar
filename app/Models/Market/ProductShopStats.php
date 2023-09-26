<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 12 Dec 2022 19:41:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Market\ProductShopStats
 *
 * @property int $id
 * @property int $product_shop_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Market\ProductShop $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats whereProductShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductShopStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductShopStats extends Model
{
    protected $table = 'product_shop_stats';

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductShop::class);
    }
}
