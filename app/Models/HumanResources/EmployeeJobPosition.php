<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Actions\HumanResources\Employee\Hydrators\EmployeeHydrateJobPositionsShare;
use App\Actions\HumanResources\JobPosition\HydrateJobPosition;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\HumanResources\EmployeeJobPosition
 *
 * @property int $id
 * @property int $job_position_id
 * @property int $employee_id
 * @property float|null $share
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HumanResources\Employee $employee
 * @property-read \App\Models\HumanResources\JobPosition $jobPosition
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition whereJobPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition whereShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeJobPosition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmployeeJobPosition extends Pivot
{
    public $incrementing = true;

    protected $guarded = [];



    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }
}
