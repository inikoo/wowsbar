<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Task\Task
 *
 * @property int $id
 * @property int $organisation_user_id
 * @property int $task_type_id
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Task|null $activity
 * @property-read \App\Models\Task\TaskType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereOrganisationUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTaskTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TaskType::class);
    }
}
