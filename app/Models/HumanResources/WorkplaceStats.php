<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 30 Dec 2023 14:29:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\HumanResources\WorkplaceStats
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
