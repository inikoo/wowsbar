<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:20 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Enums\HumanResources\TimeTracking\TimeTrackingStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\HumanResources\TimeTracking
 *
 * @property int $id
 * @property string $slug
 * @property TimeTrackingStatusEnum $status
 * @property string $subject_type Employee|Guest
 * @property int $subject_id
 * @property int|null $workplace_id
 * @property \Carbon\Carbon|null $starts_at
 * @property \Carbon\Carbon|null $ends_at
 * @property int|null $start_clocking_id
 * @property int|null $end_clocking_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @method static Builder|TimeTracking newModelQuery()
 * @method static Builder|TimeTracking newQuery()
 * @method static Builder|TimeTracking onlyTrashed()
 * @method static Builder|TimeTracking query()
 * @method static Builder|TimeTracking whereCreatedAt($value)
 * @method static Builder|TimeTracking whereDeleteComment($value)
 * @method static Builder|TimeTracking whereDeletedAt($value)
 * @method static Builder|TimeTracking whereEndClockingId($value)
 * @method static Builder|TimeTracking whereEndsAt($value)
 * @method static Builder|TimeTracking whereId($value)
 * @method static Builder|TimeTracking whereSlug($value)
 * @method static Builder|TimeTracking whereStartClockingId($value)
 * @method static Builder|TimeTracking whereStartsAt($value)
 * @method static Builder|TimeTracking whereStatus($value)
 * @method static Builder|TimeTracking whereSubjectId($value)
 * @method static Builder|TimeTracking whereSubjectType($value)
 * @method static Builder|TimeTracking whereUpdatedAt($value)
 * @method static Builder|TimeTracking whereWorkplaceId($value)
 * @method static Builder|TimeTracking withTrashed()
 * @method static Builder|TimeTracking withoutTrashed()
 * @mixin \Eloquent
 */
class TimeTracking extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $casts = [
        'status'      => TimeTrackingStatusEnum::class,
        'starts_at'   => 'datetime',
        'ends_at'     => 'datetime'
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
                return now()->format('YmdHis');
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
