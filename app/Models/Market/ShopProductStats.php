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
 * App\Models\Market\ShopProductStats
 *
 * @property int $id
 * @property int $shop_product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Market\ShopProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats whereShopProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopProductStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopProductStats extends Model
{
    protected $table = 'shop_product_stats';

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class);
    }
}
