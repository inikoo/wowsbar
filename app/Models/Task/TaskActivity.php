<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Task\TaskActivity
 *
 * @property int $id
 * @property string $authorable_type
 * @property int $authorable_id
 * @property int|null $author_id Employee|Guest
 * @property string|null $author_type
 * @property int|null $activity_id can be social post id
 * @property string|null $activity_type
 * @property string $date
 * @property int $task_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @property-read Model|\Eloquent $activity
 * @property-read Model|\Eloquent $author
 * @property-read \App\Models\Task\Task $task
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereAuthorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereAuthorableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereAuthorableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class TaskActivity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function activity(): MorphTo
    {
        return $this->morphTo();
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
