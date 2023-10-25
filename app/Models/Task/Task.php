<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $task_type_id
 * @property string $organisation_user_id
 * @property string $date
 * @property \App\Models\Task\TaskType $type
 * @property \App\Models\Task\TaskActivity $activity
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
