<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:20 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Enums\HumanResources\Clocking\ClockingTypeEnum;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\HumanResources\Clocking
 *
 * @property int $id
 * @property string $slug
 * @property ClockingTypeEnum $type
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property int|null $time_tracking_id
 * @property int|null $workplace_id
 * @property int|null $clocking_machine_id
 * @property string $clocked_at
 * @property string|null $generator_type
 * @property int|null $generator_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by_type
 * @property int|null $deleted_by_id
 * @property int|null $source_id
 * @property-read \App\Models\HumanResources\ClockingMachine|null $clockingMachine
 * @property-read \App\Models\HumanResources\Workplace|null $workplace
 * @method static Builder|Clocking newModelQuery()
 * @method static Builder|Clocking newQuery()
 * @method static Builder|Clocking onlyTrashed()
 * @method static Builder|Clocking query()
 * @method static Builder|Clocking whereClockedAt($value)
 * @method static Builder|Clocking whereClockingMachineId($value)
 * @method static Builder|Clocking whereCreatedAt($value)
 * @method static Builder|Clocking whereDeletedAt($value)
 * @method static Builder|Clocking whereDeletedById($value)
 * @method static Builder|Clocking whereDeletedByType($value)
 * @method static Builder|Clocking whereGeneratorId($value)
 * @method static Builder|Clocking whereGeneratorType($value)
 * @method static Builder|Clocking whereId($value)
 * @method static Builder|Clocking whereNotes($value)
 * @method static Builder|Clocking whereSlug($value)
 * @method static Builder|Clocking whereSourceId($value)
 * @method static Builder|Clocking whereSubjectId($value)
 * @method static Builder|Clocking whereSubjectType($value)
 * @method static Builder|Clocking whereTimeTrackingId($value)
 * @method static Builder|Clocking whereType($value)
 * @method static Builder|Clocking whereUpdatedAt($value)
 * @method static Builder|Clocking whereWorkplaceId($value)
 * @method static Builder|Clocking withTrashed()
 * @method static Builder|Clocking withoutTrashed()
 * @mixin Eloquent
 */
class Clocking extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $casts = [
        'type'      => ClockingTypeEnum::class
    ];


    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return $this->clocked_at;
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }

    public function clockingMachine(): BelongsTo
    {
        return $this->belongsTo(ClockingMachine::class);
    }

}
