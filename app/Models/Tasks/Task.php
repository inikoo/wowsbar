<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 22:28:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tasks;

use App\Models\Auth\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Tasks\Task
 *
 * @property int $id
 * @property int $task_type_id
 * @property int|null $activity_id e.g. social post id
 * @property string|null $activity_type
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @property-read Model|\Eloquent $activity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganisationUser> $organisationUsers
 * @property-read int|null $organisation_users_count
 * @property-read \App\Models\Tasks\TaskType $taskType
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTaskTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organisationUsers(): BelongsToMany
    {
        return $this->belongsToMany(OrganisationUser::class)->using(OrganisationUserTask::class);
    }

    public function activity(): MorphTo
    {
        return $this->morphTo();
    }

    public function taskType(): BelongsTo
    {
        return $this->belongsTo(TaskType::class);
    }
}
