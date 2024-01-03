<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 22:45:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
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
 * @property int $number_clocking_machines_type_static_nfc
 * @property int $number_clocking_machines_type_mobile_app
 * @property-read \App\Models\HumanResources\Workplace $workplace
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereNumberClockingMachines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereNumberClockingMachinesTypeMobileApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkplaceStats whereNumberClockingMachinesTypeStaticNfc($value)
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
