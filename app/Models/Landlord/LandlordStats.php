<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:10:04 Malaysia Time, Pantai Lembeng,, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Landlord\LandlordStats
 *
 * @property int $id
 * @property int $landlord_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Landlord\Landlord $landlord
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats whereLandlordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LandlordStats extends Model
{
    protected $table = 'landlord_stats';

    protected $guarded = [];

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class);
    }
}
