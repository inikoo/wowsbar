<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 09 Jul 2023 23:17:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tenancy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Tenancy\UserStats
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenancy\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereUserId($value)
 * @mixin \Eloquent
 */
class UserStats extends Model
{
    protected $table = 'user_stats';

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
