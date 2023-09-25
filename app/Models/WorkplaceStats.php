<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Sep 2023 14:19:34 Malaysia Time, plane Bali-KL
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models;

use App\Models\HumanResources\Workplace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\WorkplaceStats
 *
 * @property int $id
 * @property int $workplace_id
 * @property int $number_clocking_machines
 * @property int $number_clockings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Workplace $workplace
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereNumberClockingMachines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereNumberClockings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereWorkplaceId($value)
 * @mixin \Eloquent
 */
class WorkplaceStats extends Model
{
    protected $guarded = [];

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }
}
