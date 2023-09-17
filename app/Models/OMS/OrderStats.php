<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:20 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\OMS;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\OMS\OrderStats
 *
 * @property-read \App\Models\OMS\Order|null $order
 * @method static Builder|OrderStats newModelQuery()
 * @method static Builder|OrderStats newQuery()
 * @method static Builder|OrderStats query()
 * @mixin Eloquent
 */
class OrderStats extends Model
{
    use UsesTenantConnection;

    protected $table = 'order_stats';

    protected $guarded = [];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
