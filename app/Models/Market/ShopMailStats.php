<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Market\ShopMailStats
 *
 * @property-read \App\Models\Market\Shop $shop
 * @method static Builder|ShopMailStats newModelQuery()
 * @method static Builder|ShopMailStats newQuery()
 * @method static Builder|ShopMailStats query()
 * @mixin Eloquent
 */
class ShopMailStats extends Model
{
    protected $table = 'shop_mail_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
