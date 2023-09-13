<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 11:45:08 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Organisation\Market\ShopFulfilmentStats
 *
 * @property-read \App\Models\Organisation\Market\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|ShopFulfilmentStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopFulfilmentStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopFulfilmentStats query()
 * @mixin \Eloquent
 */
class ShopFulfilmentStats extends Model
{
    use UsesTenantConnection;

    protected $table = 'shop_fulfilment_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
